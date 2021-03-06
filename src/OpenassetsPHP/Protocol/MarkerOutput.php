<?php
namespace youkchan\OpenassetsPHP\Protocol;
use BitWasp\Buffertools\Buffer;
use BitWasp\Bitcoin\Script\Opcodes;
use youkchan\OpenassetsPHP\Util;
use BitWasp\Bitcoin\Script\ScriptFactory;
use BitWasp\Buffertools\Buffertools;
use Exception;


class MarkerOutput
{
    const OAP_MARKER = "4f41";
    const VERSION = "0100";
    const MAX_ASSET_QUANTITY = 2 ** 63 -1;

    protected $asset_quantities;
    protected $metadata;

    public function __construct($asset_quantities, $metadata)
    {
        $this->asset_quantities = $asset_quantities;
        if (is_null($metadata)) {
            $this->metadata =  "";
        } else {
            $this->metadata = $metadata;
        }
    }

    public function build_script()
    {
        $buffer = Buffer::hex($this->serialize_payload());
        return ScriptFactory::sequence([Opcodes::OP_RETURN, $buffer]);
    }

    public function get_metadata()
    {
        return $this->metadata;
    }

    public function get_asset_quantities()
    {
        return $this->asset_quantities;
    }

    public function serialize_payload()
    {
        $payload = [self::OAP_MARKER, self::VERSION];
        $buffer = Buffertools::numToVarInt(count($this->asset_quantities));
        $payload[] = self::get_sort_hex($buffer);
        foreach($this->asset_quantities as $quantity) {
            $payload[] = Util::encode_leb128($quantity)[1];
        }
        $buffer = Buffertools::numToVarInt(strlen($this->metadata));
        $payload[] = self::get_sort_hex($buffer);
        //$tmp = null;
        $meta_buffer = new Buffer($this->metadata);
        $payload[] = $meta_buffer->getHex();
        return implode('', $payload);
    }

    public static function deserialize_payload($payload)
    {
        if (self::is_valid_payload($payload) !== true) {
            return null;
        }

        $payload = substr($payload, strlen(self::OAP_MARKER.self::VERSION));
        $parsed_asset_quantity = self::parse_asset_quantity($payload);
        $asset_quantity = $parsed_asset_quantity[0];
        $payload = $parsed_asset_quantity[1];
        $base = null;
        foreach(str_split($payload, 2) as $byte) {
            $base .= Buffer::hex($byte)->getInt() >= 128 ? $byte : $byte.'|';
        }

        $base = substr($base, 0, -1);
        $data = explode('|', $base);
        $list = implode(array_slice($data, 0, $asset_quantity));
        $asset_quantities = Util::decode_leb128($list);
        $metaHex = Buffer::hex($payload)->slice(Buffer::hex($list)->getSize() + 1);
        $metadata = empty($metaHex) ? NULL : $metaHex->getBinary();
        return new MarkerOutput($asset_quantities, $metadata);
    }

    public static function get_sort_hex(Buffer $buffer)
    {
        switch ($buffer->slice(0,1)->getHex()) {
        case 'fd':
            $newHex = $buffer->slice(0,1)->getHex().
                $buffer->slice(2,3)->getHex().
                $buffer->slice(1,1)->getHex();
            return Buffer::hex($newHex)->getHex();
        case 'fe':
            $newHex = $buffer->slice(0,1)->getHex().
                $buffer->slice(4,5)->getHex().
                $buffer->slice(3,4)->getHex().
                $buffer->slice(1,1)->getHex();
            return Buffer::hex($newHex)->getHex();
        default:
            return $buffer->getHex();
        }
    }

    public static function parse_asset_quantity($payload)
    {
        $buffer = Buffer::hex($payload);
        switch ($buffer->slice(0,1)->getHex()) {
        case "fd":
            return [$buffer->slice(1,2)->getInt(), $buffer->slice(3)->getHex()];
        case 'fe':
            return [$buffer->slice(1,4)->getInt(), $buffer->slice(5)->getHex()];
        default:
            return [$buffer->slice(0,1)->getInt(), $buffer->slice(1)->getHex()];
        }
    }

    //TODO Script$B$G<u$1$l$PNI$$5$$,$9$k(B
    public static function parse_script(Buffer $buf)
    {
        $script = ScriptFactory::create($buf)->getScript();
        $parse = $script->getScriptParser()->decode();
        if ($parse[0]->getOp() == Opcodes::OP_RETURN) {
            $hex = $parse[1]->getData()->getHex();
            return self::is_valid_payload($hex) ? $hex : null;
        } else {
            return null;
        }
    }
    public static function is_valid_payload($payload) {

        if (is_null($payload)) {
            return false;
        }
        if (substr($payload,  0, 8) !== self::OAP_MARKER.self::VERSION) {
            return false;
        }
        //ToDo:: readLeb128
        //ToDo:: readVarInteger
        return true;

    }
}
