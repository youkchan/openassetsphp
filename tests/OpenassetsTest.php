<?php
use PHPUnit\Framework\TestCase;
use youkchan\OpenassetsPHP\Openassets;
use youkchan\OpenassetsPHP\Cache\OutputCache;
use youkchan\OpenassetsPHP\Protocol\OutputType;
use youkchan\OpenassetsPHP\Protocol\OaTransactionOutput;
use BitWasp\Bitcoin\Script\ScriptFactory;
require_once "Bootstrap.php";

class OpenassetsTest extends TestCase
{

    private $openassets;
    private $coin_name;

    public function setUp(){
        $params = array(
         //   "network" =>"monacoinTestnet", 
              "rpc_user" => "mona",
              "rpc_password" => "mona",
        );
        $this->openassets = new Openassets($params); 
        $this->coin_name = get_run_coin_name();
        ini_set('xdebug.var_display_max_children', -1);
        ini_set('xdebug.var_display_max_data', -1);
        ini_set('xdebug.var_display_max_depth', -1);

    }
 
    public function test_list_unspent(){
//DONE
/*
        $result = array();
        if ($this->coin_name == "litecoin") {
            $result = $this->openassets->list_unspent(["bWy1zdjy9Le6u9E9GBxfXviKqnparoNZRWA"]);
        }
        else if ($this->coin_name == "monacoin") {
            //$result = $this->openassets->list_unspent(["b6NdFLNHmvbMafTrdRKuoKDnTnVuesayKj6"]);
            $result = $this->openassets->list_unspent();
        }
        else {
            $this->fail("node not run.");
        }
        foreach ($result as $item) {
            $this->assertTrue(is_object($item));
            $object_list = explode("\\",get_class($item));
            $this->assertEquals(end($object_list), "SpendableOutput");
        }*/
    }

    public function test_get_balance() {
        $result = $this->openassets->get_balance();
//var_dump($result);
    }

    public function test_issue_asset() {
        //$result = $this->openassets->issue_asset("bWy1zdjy9Le6u9E9GBxfXviKqnparoNZRWA",100);
        //$result = $this->openassets->issue_asset("bXCcjk3wL8GAtkeoxzzcVj2nfSAN6XCtYEK",100, "https://test.co.jp",null ,50000);
    }

    public function test_send_asset() {
  //      $result = $this->openassets->send_asset("bWy1zdjy9Le6u9E9GBxfXviKqnparoNZRWA","ocqrkQqGpnWQKcdJAdrid5Ur9os7JQRbqb",50 ,"bXCcjk3wL8GAtkeoxzzcVj2nfSAN6XCtYEK", 50000);
        //$result = $this->openassets->transfer_asset("bWy1zdjy9Le6u9E9GBxfXviKqnparoNZRWA",100);
//var_dump($result);
    }


    public function test_get_unspent_outputs(){
        //$address = ['mzi2Dbx1Q9gdFHhJga2rEhyMaUT5QuMrk3'];
        $address = ['mx7DJCfEW3BXyNatnpXio5VLbqjspFgqdd'];
        //$result = $this->openassets->get_unspent_outputs($address);
        //$address_list = ['MCfN6CUST7TtoDhGNhocfMstStjUr8SFNT'];
        //$address_list = ['mzi2Dbx1Q9gdFHhJga2rEhyMaUT5QuMrk3'];
        //$address_list = ['3EktnHQD7RiAE6uzMj2ZifT9YgRrkSgzQX'];
        //$result = $this->openassets->get_unspent_outputs($address_list);
        //$this->assertSame($result,'OK');
    }


    public function test_load_cached_transaction(){
        $transaction_id = "f23407472e1f90a91d9473c9076dc8f3d6b6748290775a125388062b366766cf";
        $result_serialized_transaction_comp ="0200000005bcb108539057c6e8d83e811354269e1ed3ba39a0d10c5f8e35590fda7be6fba4000000006a47304402207395f0ee5489565b097f04204e2b147e71b4380c4e470f8e23a40a98e86905dc0220710774e1162bc08b2bfbed98c66bc70b26009104078efd77a769c7197502e8ed012103a31a542ddf0e9b829d47b8a9d19d1346430416eab2fbdc55b5074b777c335a74feffffffb8b84d72bfa3c07ebbfbc3af582408f90ceaacf0bd1efacc760696e2d5b6c27d000000006b483045022100c3f6f4316a592fd4dff30ae0110de69c83042ff5793addf2cb3d6145f7e283f802202324159b2d88dc115837f2d8ae4c615e02fce4dd75c6553a88a461b6fad40cec012103a31a542ddf0e9b829d47b8a9d19d1346430416eab2fbdc55b5074b777c335a74feffffff7c2845ab043881b3097d44c87b4cbde79b1db80171184512c2089643782bc2d7000000006b483045022100b62102737b0b44f6de33de5c1aaf101ede0542e4ffeda8cfa931f5b493ef63d702205b3e2be6737fe21bb211c21c9dd63c66dc9e9243ff245478a4257b0f8b2ef8db012103a31a542ddf0e9b829d47b8a9d19d1346430416eab2fbdc55b5074b777c335a74feffffff44751ea1d8938cb99d14a83550f5054cdcff836318c9b597188da53d471c9b42000000006b48304502210080a097aaa5e8efb6a756996986ec88ca18157530a80ac9d5cb8eb91d4f9dc7160220536abfaee20f90ee7a1972fbb55f426b49593b131da5baaa56f8c8ea70b031c3012103a31a542ddf0e9b829d47b8a9d19d1346430416eab2fbdc55b5074b777c335a74feffffff993e12c9223fa08f968debfe4cb634f1b2de9093822ee0a3e0024a0ef762eb4e010000006b483045022100b33e7c30702b211dcb5ab1129a64e7715457597768ce88c60e98b3b81b641165022008d8a36bc078badd2948f6c709bf418bd10c19152e77bd42acaed76902a765680121021378979d553a0573709a6ca7721547c0ab58c41b6ac2fba18166092e7711426dfeffffff02a0443423000000001976a914916016b332cd05dd041d8995da4796561249679c88acc0a5bb29050000001976a914cb5cde5d340d498c3be4533891173ec052878ab788ac64400200";
        $params = array(
              "cache" => null,
              "rpc_user" => "mona",
              "rpc_password" => "mona",
        );
        $openassets = new Openassets($params); 
        $no_cache_result = $openassets->load_cached_transaction($transaction_id);
        $this->assertSame($result_serialized_transaction_comp, $no_cache_result);

       
        $path = "cache_test.db"; 
        $params = array(
              "cache" => $path,
              "rpc_user" => "mona",
              "rpc_password" => "mona",
        );
        if(file_exists($path)) {
            unlink($path); //delete cache.db
        }
        $openassets = new Openassets($params); 
        $yet_cache_result = $openassets->load_cached_transaction($transaction_id);
        $this->assertSame($result_serialized_transaction_comp, $yet_cache_result);
        $cache_result = $openassets->load_cached_transaction($transaction_id);
        $this->assertSame($result_serialized_transaction_comp, $cache_result);

    }

    public function test_load_cached_output() {
 
        $tx_id = "7ed86d1c2824ea14bf8a2fe27202a1d229a4f58db52e2ba1ed13cf36765deaac";
        $index = 0;
        $params = array(
              "cache" => null,
              "rpc_user" => "mona",
              "rpc_password" => "mona",
        );
        $openassets = new Openassets($params); 
        $result = $openassets->load_cached_output($tx_id, $index);
        $this->assertSame($result, false);

        $path = "cache_test.db"; 
        $params = array(
              "cache" => $path,
              "rpc_user" => "mona",
              "rpc_password" => "mona",
        );
        if(file_exists($path)) {
            unlink($path); //delete cache.db
        }
        $openassets = new Openassets($params); 
        $result = $openassets->load_cached_output($tx_id, $index);
        $this->assertSame($result, false);

        $output_cache = new OutputCache($path);
        $value = 100;
        $script = "76a914cb5cde5d340d498c3be4533891173ec052878ab788ac";
        $asset_id = "AGHhobo7pVQN5fZWqv3rhdc324ryT7qVTB";
        $asset_quantity = 200;
        $output_type = OutputType::ISSUANCE;
        $metadata = "u=https://goo.gl/Q0NZfe";
        $output_cache->set($tx_id, $index, $value, $script, $asset_id, $asset_quantity, $output_type, $metadata);  
        $result = $openassets->load_cached_output($tx_id, $index);
        $this->assertEquals($result->value, $value);
        $this->assertEquals($result->script->getHex(), $script);
        $this->assertEquals($result->asset_id, $asset_id);
        $this->assertEquals($result->asset_quantity, $asset_quantity);
        $this->assertEquals($result->output_type, $output_type);
        $this->assertEquals($result->metadata, $metadata);
    }

    public function test_set_cached_output() {
        $value = [590628000,600];
        $script = ["76a914916016b332cd05dd041d8995da4796561249679c88ac","76a914cb5cde5d340d498c3be4533891173ec052878ab788ac"];
        $asset_id = ["","oWDNLde2LweGTgsVgtx6XcNNDZvm8kWnj1"];
        $asset_quantity = [0,600];
        $output_type = [0, 3];
        $metadata = ["","u=http://google.com"]; 
        $outputs[] = new OaTransactionOutput($value[0], ScriptFactory::fromHex($script[0]), $asset_id[0], $asset_quantity[0], $output_type[0], $metadata[0]);
        $outputs[] = new OaTransactionOutput($value[1], ScriptFactory::fromHex($script[1]), $asset_id[1], $asset_quantity[1], $output_type[1], $metadata[1]);
        $tx_id = "07d523952a566cb713f660cd8e2f58ee70821e30f0d0056e50a8036b5536ac77";
        
        $params = array(
              "cache" => null,
              "rpc_user" => "mona",
              "rpc_password" => "mona",
        );
        $openassets = new Openassets($params); 
        $result = $openassets->set_cached_output($tx_id, $outputs);
        $this->assertSame($result, false);

        $path = "cache_test.db"; 
        $params = array(
              "cache" => $path,
              "rpc_user" => "mona",
              "rpc_password" => "mona",
        );
        if(file_exists($path)) {
            unlink($path); //delete cache.db
        }
        $openassets = new Openassets($params); 
        $openassets->set_cached_output($tx_id, $outputs);
        $output_cache = new OutputCache($path);
        $result = $output_cache->get($tx_id, 0);
        $this->assertEquals($result->value, $value[0]);
        $this->assertEquals($result->script->getHex(), $script[0]);
        $this->assertEquals($result->asset_id, $asset_id[0]);
        $this->assertEquals($result->asset_quantity, $asset_quantity[0]);
        $this->assertEquals($result->output_type, $output_type[0]);
        $this->assertEquals($result->metadata, $metadata[0]);

        $result = $output_cache->get($tx_id, 1);
        $this->assertEquals($result->value, $value[1]);
        $this->assertEquals($result->script->getHex(), $script[1]);
        $this->assertEquals($result->asset_id, $asset_id[1]);
        $this->assertEquals($result->asset_quantity, $asset_quantity[1]);
        $this->assertEquals($result->output_type, $output_type[1]);
        $this->assertEquals($result->metadata, $metadata[1]);

    }

    public function test_get_output() {
        //$transaction_id = "da3851496a0cdf5447d53d1a735085532be59b45aadc8961ed464853c283b61c";
        $transaction_id = "54844e349add3a8fe40034072679dcf067f44bcc7571cfc65dc82b031fda6e69"; //issue
        //$transaction_id = "480b6c74a188bfb5c69966f0156c2122ebe134b7aedd650cef50af26a5174746"; //send

        //var_dump($this->openassets->get_output($transaction_id,0));
    }

    /*public function test_parse_issuance_p2sh_pointer() {
        $te = '47304402202254f7da7c3fe2bf2a4dd2c3e255aa3ad61415550f648b564aea335f8fcd3d92022062eab5c01a5e33eb726f976ebd3b35d3991f8a45da56d64e1cd3fd5178f8c9a6012102effb2edfcf826d43027feae226143bdac058ad2e87b7cec26f97af2d357ddefa3217753d68747470733a2f2f676f6f2e676c2f626d564575777576a9148911455a265235b2d356a1324af000d4dae0326288ac';
        return $this->openassets->parse_issuance_p2sh_pointer($te);
        
    }*/

    public function test_create_transaction_builder() {
//        $this->openassets->create_transaction_builder();
    }

}
