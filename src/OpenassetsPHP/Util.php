<?php
namespace youkchan\OpenassetsPHP;
use BitWasp\Bitcoin\Base58;
use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\Buffertools;
use BitWasp\Bitcoin\Address\AddressCreator;
use TheFox\Utilities\Leb128;
use youkchan\OpenassetsPHP\Network;
use BitWasp\Bitcoin\Crypto\Hash;
use BitWasp\Bitcoin\Script\Classifier\OutputClassifier;
use BitWasp\Bitcoin\Script\ScriptFactory;
use BitWasp\Bitcoin\Script\Opcodes;
use Exception;

class Util
{

    const OA_VERSION_BYTE = 23;
    const OA_VERSION_BYTE_TESTNET = 115;
    const OA_NAMESPACE = 19;

    public static function convert_oa_address_to_address($oa_address) {
        $decode_address = Base58::decode($oa_address);
        $btc_address = $decode_address->slice(1, -4);
        $btc_checksum = Base58::checksum($btc_address);
        return Base58::encode(Buffertools::concat($btc_address , $btc_checksum));
    }

    public static function convert_address_to_oa_address($btc_address) {
        $decode_address = Base58::decode($btc_address);
        if ($decode_address->getSize() == 47) {
            $decode_address = '0'.$decode_address->getHex();
        } else {
            $decode_address = $decode_address->getHex();
        }
        $named_address = dechex(self::OA_NAMESPACE).substr($decode_address, 0, -8);
        $oa_checksum = Base58::checksum(Buffer::hex($named_address));
        return Base58::encode(Buffer::hex($named_address.$oa_checksum->getHex()));
    }

    public static function validate_addresses($address_list, $network) {

        $address_creator = new AddressCreator();
        foreach ($address_list as $address) {
            try {
                $address_creator->fromString($address, $network);
            } catch (Exception $e){
                throw new Exception($address . " is invalid coin address" );
            }    
        }
    }

    public static function validate_oa_addresses($oa_address_list, $network) {
        $address_list = [];
        foreach ($oa_address_list as $oa_address) {
            try {
                $address_list[] = self::convert_oa_address_to_address($oa_address);
            } catch (Exception $e){
                throw new Exception($oa_address . " is invalid openasset address" );
            }    
        }
        self::validate_addresses($address_list, $network);
    }

    public static function encode_leb128($x)
    {
        if ($x < 0) {
            throw new InvalidArgumentException("Value can't be < 0. Use sencode().", 10);
        }
        $str = '';
        do {
            $char = $x & 0x7f;
            $x >>= 7;
            if($x > 0){
                $char |= 0x80;
            }
            $str .= chr($char);
        } while ($x);
        
        return unpack('H*', $str);
    }

    public static function decode_leb128($leb128)
    {
        $base = null;
        $bytes = str_split($leb128, 2);
        $num_items = count($bytes);
        $i = 0;
        foreach($bytes as $byte) {
            if (++$i !== $num_items) {
                $base .= Buffer::hex($byte)->getInt() >= 128 ? $byte : $byte.'|';
            } else {
                $base .= $byte;
            }
        };
        $data = explode('|', $base);
        $x = 0;
        foreach ($data as $str) {
            $len = Leb128::udecode(pack('H*', $str), $x);
            $res[] = $x;
        }
        return $res;
    }

    public static function script_to_asset_id($script, $network) {
         $hash = Hash::sha256ripe160($script->getBuffer());
         return self::hash_to_asset_id($hash, $network);
    }
    
    public static function hash_to_asset_id($hash, $network) {
        $prefix_buffer = Buffer::hex(strval(dechex(self::oa_version_byte($network))));
        $hash = Buffertools::concat($prefix_buffer,$hash);
        $checksum = Base58::checksum($hash);
        return Base58::encode(Buffertools::concat($hash , $checksum));
    }

    public static function oa_version_byte($network) {
        if ($network->get_p2pkh_address_prefix() == "6f" ){
           return self::OA_VERSION_BYTE_TESTNET;
        } else {
           return self::OA_VERSION_BYTE;
        }
    }

    public static function coin_to_satoshi($coin) {
        return $coin * 100000000;
    }

    public static function array_flatten(array $arr) 
    {
        $result = array();
        foreach ($arr as $item) {
            if (is_array($item)) {
                $result = array_merge($result, array_flatten($item));
            } else {
                $result[] = $item;
            }
        }
        return $result;
    }

    public static function script_to_address($script,$network)
    {
        $address_creator = new AddressCreator();
        $classifier = new OutputClassifier();
        $type = $classifier->classify($script);
        if ($type == OutputClassifier::MULTISIG) {
            $multiSig = new Multisig($script);
            $res = [];
            foreach($multiSig->getKeys() as $key) {
                $res[] = $key->getAddress()->getAddress();
            }
            return $res;
        } elseif ($type == OutputClassifier::PAYTOPUBKEY) {
            $pubkey = new PayToPubkey($script);
            return $pubkey[0]->getAddress();
        } elseif ($type == OutputClassifier::PAYTOSCRIPTHASH) {
            $script = $address_creator->fromScript($script, $network);
            return $script->getAddress();
        } elseif ($type == OutputClassifier::PAYTOPUBKEYHASH) {
            return $address_creator->fromOutputScript($script)->getAddress();
        } 
    }

}
