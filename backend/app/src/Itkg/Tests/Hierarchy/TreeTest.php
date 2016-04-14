<?php
namespace Itkg\Tests\Hierarchy;


use Itkg\Hierarchy\Tree;

class TreeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Tree
     */
    protected $tree;

    public function setUp()
    {
        $this->tree = new Tree('test','id','pid');
        $options = [];
        $options['tid'] = 28;
        $options['LIB_HIERARCHY'] = '/hierarchy/';
        $options['LIB_PATH'] = '/path/';
        $this->tree->setOptions($options);
    }

    /**
     *
     * @dataProvider providerGetPublicationClass
     * @param array $node
     * @param string $classExpected
     */
    public function testAddNode(array $node, $classExpected)
    {

        $node['lib'] = $classExpected;
        $node['id'] = 1;
        $this->tree->setReadOnly(false);
        $res = $this->tree->addNode($node);
        $this->assertContains($classExpected,$res->class);
        $this->assertNotContains('user-readonly',$res->class );

        // test si utilisateur readonly même classe mais classe user-readonly en plus
        $this->tree->setReadOnly(true);
        $res = $this->tree->addNode($node);
        $this->assertContains($classExpected,$res->class);
        $this->assertContains('user-readonly', $res->class);

    }


    /**
     * @return array
     */
    public function providerGetPublicationClass()
    {

        $dataSet = [];
        /**
         * Jeu de donnée page Hors Ligne
         */
        //page hors ligne enregistré jamais publié
        $dataSet[] = [['status'=>0,'current_version'=>null,'draft_version'=>1], 'grey'];
        $dataSet[] = [['status'=>0,'current_version'=>null,'draft_version'=>1,'start'=>'01/01/1970 11:11'], 'grey'];
        $dataSet[] = [['status'=>0,'current_version'=>null,'draft_version'=>1,'start'=>'31/12/2999 11:11'], 'grey'];
        $dataSet[] = [['status'=>0,'current_version'=>null,'draft_version'=>1,'end'=>'01/01/1970 11:11'], 'grey'];
        $dataSet[] = [['status'=>0,'current_version'=>null,'draft_version'=>1,'end'=>'31/12/2999 11:11'], 'grey'];

        /**
         * Jeu de donnée page Hors Ligne Version = 1 => red
         */
        $dataSet[] = [['status'=>0,'current_version'=>1,'draft_version'=>1], 'red'];
        $dataSet[] = [['status'=>0,'current_version'=>1,'draft_version'=>1,'start'=>'01/01/1970 11:11'], 'red'];
        $dataSet[] = [['status'=>0,'current_version'=>1,'draft_version'=>1,'start'=>'31/12/2999 11:11'], 'red'];
        $dataSet[] = [['status'=>0,'current_version'=>1,'draft_version'=>1,'end'=>'01/01/1970 11:11'], 'red'];
        $dataSet[] = [['status'=>0,'current_version'=>1,'draft_version'=>1,'end'=>'31/12/2999 11:11'], 'red'];

        /**
         * Jeu de donnée page Hors Ligne Version > 1 => red
         */

        //  Brouillon et a publiéé
        // pas de date
        $dataSet[] = [['status'=>0,'current_version'=>2,'draft_version'=>3], 'red'];
        //une date de debut dans le passé a reste vert
        $dataSet[] = [['status'=>0,'current_version'=>2,'draft_version'=>3,'start'=>'01/01/1970 11:11'], 'red'];
        //une date de debut dans le futur vert plus exclamation
        $dataSet[] = [['status'=>0,'current_version'=>2,'draft_version'=>3,'start'=>'31/12/2999 11:11'], 'red'];
        //une date de fin dans le passé c'est orange exclamation
        $dataSet[] = [['status'=>0,'current_version'=>2,'draft_version'=>3,'end'=>'01/01/1970 11:11'], 'red'];
        //une date de fin dans le futur c'est vert
        $dataSet[] = [['status'=>0,'current_version'=>2,'draft_version'=>3,'end'=>'31/12/2999 11:11'], 'red'];

        // Vesion publié
        // pas de date
        $dataSet[] = [['status'=>0,'current_version'=>3,'draft_version'=>3], 'red'];
        //une date de debut dans le passé a reste vert
        $dataSet[] = [['status'=>0,'current_version'=>3,'draft_version'=>3,'prev_start'=>'01/01/1970 11:11'], 'red'];
        //une date de debut dans le futur vert plus exclamation
        $dataSet[] = [['status'=>0,'current_version'=>3,'draft_version'=>3,'prev_start'=>'31/12/2999 11:11'], 'red'];
        //une date de fin dans le passé c'est orange exclamation
        $dataSet[] = [['status'=>0,'current_version'=>3,'draft_version'=>3,'prev_end'=>'01/01/1970 11:11'], 'red'];
        //une date de fin dans le futur c'est vert
        $dataSet[] = [['status'=>0,'current_version'=>3,'draft_version'=>3,'prev_end'=>'31/12/2999 11:11'], 'red'];


        /**
         * Jeu de donnée page En Ligne Version = 1
         */
        // NB: si version publié prev_start=start et prev_end= end obligatoirement
        // pas de date de mise la classe est verte
        $dataSet[] = [['status'=>1,'current_version'=>1,'draft_version'=>1], 'green'];
        //une date de debut dans le passé a reste vert
        $dataSet[] = [['status'=>1,'current_version'=>1,'draft_version'=>1,'prev_start'=>'01/01/1970 11:11'], 'green'];
        //une date de debut dans le futur vert plus exclamation
        $dataSet[] = [['status'=>1,'current_version'=>1,'draft_version'=>1,'prev_start'=>'31/12/2999 11:11'], 'green_oh'];
        //une date de fin dans le passé c'est orange exclamation
        $dataSet[] = [['status'=>1,'current_version'=>1,'draft_version'=>1,'prev_end'=>'01/01/1970 11:11'], 'orange_oh'];
        //une date de fin dans le futur c'est vert
        $dataSet[] = [['status'=>1,'current_version'=>1,'draft_version'=>1,'prev_end'=>'31/12/2999 11:11'], 'green'];


        /**
         * Jeu de donnée page En Ligne Version > 1
         */

        //  Brouillon et a publiéé
        // pas de date
        $dataSet[] = [['status'=>1,'current_version'=>2,'draft_version'=>3], 'green'];
        //une date de debut dans le passé a reste vert
        $dataSet[] = [['status'=>1,'current_version'=>2,'draft_version'=>3,'prev_start'=>'01/01/1970 11:11'], 'green'];
        //une date de debut dans le futur vert plus exclamation
        $dataSet[] = [['status'=>1,'current_version'=>2,'draft_version'=>3,'prev_start'=>'31/12/2999 11:11'], 'green_oh'];
        //une date de fin dans le passé c'est orange exclamation
        $dataSet[] = [['status'=>1,'current_version'=>2,'draft_version'=>3,'prev_end'=>'01/01/1970 11:11'], 'orange_oh'];
        //une date de fin dans le futur c'est vert
        $dataSet[] = [['status'=>1,'current_version'=>2,'draft_version'=>3,'prev_end'=>'31/12/2999 11:11'], 'green'];

        // Vesion publié
        // NB: si version publié prev_start=start et prev_end= end obligatoirement
        // pas de date
        $dataSet[] = [['status'=>1,'current_version'=>3,'draft_version'=>3], 'green'];
        //une date de debut dans le passé a reste vert
        $dataSet[] = [['status'=>1,'current_version'=>3,'draft_version'=>3,'prev_start'=>'01/01/1970 11:11'], 'green'];
        //une date de debut dans le futur vert plus exclamation
        $dataSet[] = [['status'=>1,'current_version'=>3,'draft_version'=>3,'prev_start'=>'31/12/2999 11:11'], 'green_oh'];
        //une date de fin dans le passé c'est orange exclamation
        $dataSet[] = [['status'=>1,'current_version'=>3,'draft_version'=>3,'prev_end'=>'01/01/1970 11:11'], 'orange_oh'];
        //une date de fin dans le futur c'est vert
        $dataSet[] = [['status'=>1,'current_version'=>3,'draft_version'=>3,'prev_end'=>'31/12/2999 11:11'], 'green'];

        return $dataSet;

    }


    protected function getNodes()
    {
        $nodes = [];
        $nodes[] = ['status'=>1,'current_version'=>1,'draft_version'=>1,'id'=>1,'pid'=>0,'lib'=>'root', 'order'=>1,'page_general' => 1,'langue_id'=>1,'path'=>'path','url'=>'url'];
        $nodes[] = ['status'=>1,'current_version'=>1,'draft_version'=>1,'id'=>2,'pid'=>0,'lib'=>'root', 'order'=>2,'page_general' => 0,'langue_id'=>1,'path'=>'path','url'=>'url'];
        $temp = $this->providerGetPublicationClass();
        foreach($temp as $idx => $nodeData) {
            $nodeData[0]['id'] = $idx+5;
            $nodeData[0]['pid'] = 1;
            $nodeData[0]['order'] = $idx+2;
            $nodeData[0]['lib'] = $nodeData[1];
            $nodeData[0]['page_general'] = 0;
            $nodeData[0]['langue_id'] = 1;
            $nodeData[0]['path'] = 'path';
            $nodeData[0]['url'] = 'url';
            $nodes[] = $nodeData[0];
        }

        return $nodes;
    }


    public function testGetTree()
    {
        $nodes  = $this->getNodes();
        $this->tree->addTabNode($nodes);
        $this->tree->setOrder("order", "ASC");
        $this->tree->setTreeType('menu');
        $return = $this->tree->getTree();

        $this->assertInternalType('string',$return);
    }


    public function testBuildJsonTree()
    {
        $nodes  = $this->getNodes();

        $this->tree->addTabNode($nodes);
        $this->tree->setOrder("order", "ASC");
        $this->tree->setTreeType('menu');
        $return = $this->tree->buildJsonTree($this->tree->aNodes);

        $this->assertInternalType('array',$return);
    }
}
