<?php

namespace App\Tests\Utils;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Twig\AppExtension;

class CategoryTest extends KernelTestCase
{
    protected $mockedCategoryTreeFrontPage;
    protected $mockedCategoryTreeAdminList;
    protected $mockedCategoryTreeAdminOptionList;

    protected function setUp()
    {
        $kernel = self::bootKernel();
        $urlGenerator = $kernel->getContainer()->get('router');
        $tested_classes = [
            'CategoryTreeAdminList',
            'CategoryTreeAdminOptionList',
            'CategoryTreeFrontPage',
        ];
        foreach($tested_classes as $class)
        {
            $name = 'mocked'.$class;
            $this->$name = $this->getMockBuilder('App\Utils\\'.$class)
                ->disableOriginalConstructor()
                ->setMethods()
                ->getMock();
            $this->$name->urlGenerator = $urlGenerator;
        }
    }

    /**
     * @dataProvider dataForCategoryTreeAdminOptionList
     * @param $arrayToCompare
     * @param $arrayFromDb
     */
    public function testCategoryTreeAdminOptionList($arrayToCompare, $arrayFromDb)
    {
        $this->mockedCategoryTreeAdminOptionList->categoriesArrayFromDb = $arrayFromDb;
        $arrayFromDb = $this->mockedCategoryTreeAdminOptionList->buildTree();
        $this->assertSame($arrayToCompare, $this->mockedCategoryTreeAdminOptionList->getCategoryList($arrayFromDb));
    }

    public function dataForCategoryTreeAdminOptionList()
    {
        yield [
            [
                ['name' => 'Electronics', 'id' => 1],
                ['name' => '--Cameras', 'id' => 5],
                ['name' => '--Computers', 'id' => 6],
                ['name' => '----Laptops', 'id' => 8],
                ['name' => '------Apple', 'id' => 10],
                ['name' => '------Asus', 'id' => 11],
                ['name' => '------Dell', 'id' => 12],
                ['name' => '------Lenovo', 'id' => 13],
                ['name' => '------HP', 'id' => 14],
                ['name' => '----Desktop', 'id' => 9],
                ['name' => '--Cell Phones', 'id' => 7],
                ['name' => 'Toys', 'id' => 2],
                ['name' => 'Books', 'id' => 3],
                ['name' => '--Children\'s Books', 'id' => 15],
                ['name' => '--Kindle eBooks', 'id' => 16],
                ['name' => 'Movies', 'id' => 4],
                ['name' => '--Family', 'id' => 17],
                ['name' => '--Romance', 'id' => 18],
                ['name' => '----Romantic Comedy', 'id' => 19],
                ['name' => '----Romantic Drama', 'id' => 20],
            ],
            [
                ["name" => "Electronics","id" => 1, "parent_id" => null],
                ["name" => "Toys", "id" => 2, "parent_id" => null],
                ["name" => "Books", "id" => 3, "parent_id" => null],
                ["name" => "Movies", "id" => 4, "parent_id" => null],
                ["name" => "Cameras", "id" => 5, "parent_id" => "1"],
                ["name" => "Computers", "id" => 6, "parent_id" => "1"],
                ["name" => "Cell Phones", "id" => 7, "parent_id" => "1"],
                ["name" => "Laptops", "id" => 8, "parent_id" => "6"],
                ["name" => "Desktop", "id" => 9, "parent_id" => "6"],
                ["name" => "Apple", "id" => 10, "parent_id" => "8"],
                ["name" => "Asus", "id" => 11, "parent_id" => "8"],
                ["name" => "Dell", "id" => 12, "parent_id" => "8"],
                ["name" => "Lenovo", "id" => 13, "parent_id" => "8"],
                ["name" => "HP", "id" => 14, "parent_id" => "8"],
                ["name" => "Children's Books", "id" => 15, "parent_id" => "3"],
                ["name" => "Kindle eBooks", "id" => 16, "parent_id" => "3"],
                ["name" => "Family", "id" => 17, "parent_id" => "4"],
                ["name" => "Romance", "id" => 18, "parent_id" => "4"],
                ["name" => "Romantic Comedy", "id" => 19, "parent_id" => "18"],
                ["name" => "Romantic Drama", "id" => 20, "parent_id" => "18"],
            ],

        ];
    }

    /**
     * @dataProvider dataForCategoryTreeFrontPage
     * @param $string
     * @param $array
     * @param $id
     */
    public function testCategoryTreeFrontPage($string, $array, $id)
    {
        $this->mockedCategoryTreeFrontPage->categoriesArrayFromDb = $array;
        $this->mockedCategoryTreeFrontPage->slugger = new AppExtension();
        $main_parent_id = $this->mockedCategoryTreeFrontPage->getMainParent($id)['id'];
        $array = $this->mockedCategoryTreeFrontPage->buildTree($main_parent_id);
        $this->assertSame($string, $this->mockedCategoryTreeFrontPage->getCategoryList($array));
    }

    public function dataForCategoryTreeFrontPage()
    {
        yield [
            "<ul><li><a href='/video-list/category/childrens-books,15'>Children's Books</a></li><li><a href='/video-list/category/kindle-ebooks,16'>Kindle eBooks</a></li></ul>",
            [
                ["id" => 1, "parent_id" => null, "name" => "Electronics"],
                ["id" => 2, "parent_id" => null, "name" => "Toys"],
                ["id" => 3, "parent_id" => null, "name" => "Books"],
                ["id" => 4, "parent_id" => null, "name" => "Movies"],
                ["id" => 5, "parent_id" => 1, "name" => "Cameras"],
                ["id" => 6, "parent_id" => 1, "name" => "Computers"],
                ["id" => 7, "parent_id" => 1, "name" => "Cell Phones"],
                ["id" => 8, "parent_id" => 6, "name" => "Laptops"],
                ["id" => 9, "parent_id" => 6, "name" => "Desktop"],
                ["id" => 10, "parent_id" => 8, "name" => "Apple"],
                ["id" => 11, "parent_id" => 8, "name" => "Asus"],
                ["id" => 12, "parent_id" => 8, "name" => "Dell"],
                ["id" => 13, "parent_id" => 8, "name" => "Lenovo"],
                ["id" => 14, "parent_id" => 8, "name" => "HP"],
                ["id" => 15, "parent_id" => 3, "name" => "Children's Books"],
                ["id" => 16, "parent_id" => 3, "name" => "Kindle eBooks"],
                ["id" => 17, "parent_id" => 4, "name" => "Family"],
                ["id" => 18, "parent_id" => 4, "name" => "Romance"],
                ["id" => 19, "parent_id" => 18, "name" => "Romantic Comedy"],
                ["id" => 20, "parent_id" => 18, "name" => "Romantic Drama"]
            ],
            3,
        ];

        yield [
            "<ul><li><a href='/video-list/category/cameras,5'>Cameras</a></li><li><a href='/video-list/category/computers,6'>Computers</a><ul><li><a href='/video-list/category/laptops,8'>Laptops</a><ul><li><a href='/video-list/category/apple,10'>Apple</a></li><li><a href='/video-list/category/asus,11'>Asus</a></li><li><a href='/video-list/category/dell,12'>Dell</a></li><li><a href='/video-list/category/lenovo,13'>Lenovo</a></li><li><a href='/video-list/category/hp,14'>HP</a></li></ul></li><li><a href='/video-list/category/desktop,9'>Desktop</a></li></ul></li><li><a href='/video-list/category/cell-phones,7'>Cell Phones</a></li></ul>",
            [
                ["id" => 1, "parent_id" => null, "name" => "Electronics"],
                ["id" => 2, "parent_id" => null, "name" => "Toys"],
                ["id" => 3, "parent_id" => null, "name" => "Books"],
                ["id" => 4, "parent_id" => null, "name" => "Movies"],
                ["id" => 5, "parent_id" => 1, "name" => "Cameras"],
                ["id" => 6, "parent_id" => 1, "name" => "Computers"],
                ["id" => 7, "parent_id" => 1, "name" => "Cell Phones"],
                ["id" => 8, "parent_id" => 6, "name" => "Laptops"],
                ["id" => 9, "parent_id" => 6, "name" => "Desktop"],
                ["id" => 10, "parent_id" => 8, "name" => "Apple"],
                ["id" => 11, "parent_id" => 8, "name" => "Asus"],
                ["id" => 12, "parent_id" => 8, "name" => "Dell"],
                ["id" => 13, "parent_id" => 8, "name" => "Lenovo"],
                ["id" => 14, "parent_id" => 8, "name" => "HP"],
                ["id" => 15, "parent_id" => 3, "name" => "Children's Books"],
                ["id" => 16, "parent_id" => 3, "name" => "Kindle eBooks"],
                ["id" => 17, "parent_id" => 4, "name" => "Family"],
                ["id" => 18, "parent_id" => 4, "name" => "Romance"],
                ["id" => 19, "parent_id" => 18, "name" => "Romantic Comedy"],
                ["id" => 20, "parent_id" => 18, "name" => "Romantic Drama"]
            ],
            14,
        ];

        yield [
            "<ul><li><a href='/video-list/category/cameras,5'>Cameras</a></li><li><a href='/video-list/category/computers,6'>Computers</a><ul><li><a href='/video-list/category/laptops,8'>Laptops</a><ul><li><a href='/video-list/category/apple,10'>Apple</a></li><li><a href='/video-list/category/asus,11'>Asus</a></li><li><a href='/video-list/category/dell,12'>Dell</a></li><li><a href='/video-list/category/lenovo,13'>Lenovo</a></li><li><a href='/video-list/category/hp,14'>HP</a></li></ul></li><li><a href='/video-list/category/desktop,9'>Desktop</a></li></ul></li><li><a href='/video-list/category/cell-phones,7'>Cell Phones</a></li></ul>",
            [
                ["id" => 1, "parent_id" => null, "name" => "Electronics"],
                ["id" => 2, "parent_id" => null, "name" => "Toys"],
                ["id" => 3, "parent_id" => null, "name" => "Books"],
                ["id" => 4, "parent_id" => null, "name" => "Movies"],
                ["id" => 5, "parent_id" => 1, "name" => "Cameras"],
                ["id" => 6, "parent_id" => 1, "name" => "Computers"],
                ["id" => 7, "parent_id" => 1, "name" => "Cell Phones"],
                ["id" => 8, "parent_id" => 6, "name" => "Laptops"],
                ["id" => 9, "parent_id" => 6, "name" => "Desktop"],
                ["id" => 10, "parent_id" => 8, "name" => "Apple"],
                ["id" => 11, "parent_id" => 8, "name" => "Asus"],
                ["id" => 12, "parent_id" => 8, "name" => "Dell"],
                ["id" => 13, "parent_id" => 8, "name" => "Lenovo"],
                ["id" => 14, "parent_id" => 8, "name" => "HP"],
                ["id" => 15, "parent_id" => 3, "name" => "Children's Books"],
                ["id" => 16, "parent_id" => 3, "name" => "Kindle eBooks"],
                ["id" => 17, "parent_id" => 4, "name" => "Family"],
                ["id" => 18, "parent_id" => 4, "name" => "Romance"],
                ["id" => 19, "parent_id" => 18, "name" => "Romantic Comedy"],
                ["id" => 20, "parent_id" => 18, "name" => "Romantic Drama"]
            ],
            7,
        ];
    }

    /**
     * @dataProvider dataForCategoryTreeAdminList
     * @param $string
     * @param $array
     */
    public function testCategoryTreeAdminList($string, $array)
    {
        $this->mockedCategoryTreeAdminList->categoriesArrayFromDb = $array;
        $array = $this->mockedCategoryTreeAdminList->buildTree();
        $this->assertSame($string, $this->mockedCategoryTreeAdminList->getCategoryList($array));
    }

    public function dataForCategoryTreeAdminList()
    {
        yield [
            "<ul class='fa-ul text-left'><li><i class='fa-li fa fa-arrow-right'></i>Electronics<a href='/admin/edit_category/1'>Edit</a> <a onclick='return confirm(\"Are you sure?\");' href='/admin/delete-category/1'>Delete</a><ul class='fa-ul text-left'><li><i class='fa-li fa fa-arrow-right'></i>Cameras<a href='/admin/edit_category/5'>Edit</a> <a onclick='return confirm(\"Are you sure?\");' href='/admin/delete-category/5'>Delete</a></li><li><i class='fa-li fa fa-arrow-right'></i>Computers<a href='/admin/edit_category/6'>Edit</a> <a onclick='return confirm(\"Are you sure?\");' href='/admin/delete-category/6'>Delete</a><ul class='fa-ul text-left'><li><i class='fa-li fa fa-arrow-right'></i>Laptops<a href='/admin/edit_category/8'>Edit</a> <a onclick='return confirm(\"Are you sure?\");' href='/admin/delete-category/8'>Delete</a><ul class='fa-ul text-left'><li><i class='fa-li fa fa-arrow-right'></i>Apple<a href='/admin/edit_category/10'>Edit</a> <a onclick='return confirm(\"Are you sure?\");' href='/admin/delete-category/10'>Delete</a></li><li><i class='fa-li fa fa-arrow-right'></i>Asus<a href='/admin/edit_category/11'>Edit</a> <a onclick='return confirm(\"Are you sure?\");' href='/admin/delete-category/11'>Delete</a></li><li><i class='fa-li fa fa-arrow-right'></i>Dell<a href='/admin/edit_category/12'>Edit</a> <a onclick='return confirm(\"Are you sure?\");' href='/admin/delete-category/12'>Delete</a></li><li><i class='fa-li fa fa-arrow-right'></i>Lenovo<a href='/admin/edit_category/13'>Edit</a> <a onclick='return confirm(\"Are you sure?\");' href='/admin/delete-category/13'>Delete</a></li><li><i class='fa-li fa fa-arrow-right'></i>HP<a href='/admin/edit_category/14'>Edit</a> <a onclick='return confirm(\"Are you sure?\");' href='/admin/delete-category/14'>Delete</a></li></ul></li><li><i class='fa-li fa fa-arrow-right'></i>Desktop<a href='/admin/edit_category/9'>Edit</a> <a onclick='return confirm(\"Are you sure?\");' href='/admin/delete-category/9'>Delete</a></li></ul></li><li><i class='fa-li fa fa-arrow-right'></i>Cell Phones<a href='/admin/edit_category/7'>Edit</a> <a onclick='return confirm(\"Are you sure?\");' href='/admin/delete-category/7'>Delete</a></li></ul></li><li><i class='fa-li fa fa-arrow-right'></i>Toys<a href='/admin/edit_category/2'>Edit</a> <a onclick='return confirm(\"Are you sure?\");' href='/admin/delete-category/2'>Delete</a></li><li><i class='fa-li fa fa-arrow-right'></i>Books<a href='/admin/edit_category/3'>Edit</a> <a onclick='return confirm(\"Are you sure?\");' href='/admin/delete-category/3'>Delete</a><ul class='fa-ul text-left'><li><i class='fa-li fa fa-arrow-right'></i>Children's Books<a href='/admin/edit_category/15'>Edit</a> <a onclick='return confirm(\"Are you sure?\");' href='/admin/delete-category/15'>Delete</a></li><li><i class='fa-li fa fa-arrow-right'></i>Kindle eBooks<a href='/admin/edit_category/16'>Edit</a> <a onclick='return confirm(\"Are you sure?\");' href='/admin/delete-category/16'>Delete</a></li></ul></li><li><i class='fa-li fa fa-arrow-right'></i>Movies<a href='/admin/edit_category/4'>Edit</a> <a onclick='return confirm(\"Are you sure?\");' href='/admin/delete-category/4'>Delete</a><ul class='fa-ul text-left'><li><i class='fa-li fa fa-arrow-right'></i>Family<a href='/admin/edit_category/17'>Edit</a> <a onclick='return confirm(\"Are you sure?\");' href='/admin/delete-category/17'>Delete</a></li><li><i class='fa-li fa fa-arrow-right'></i>Romance<a href='/admin/edit_category/18'>Edit</a> <a onclick='return confirm(\"Are you sure?\");' href='/admin/delete-category/18'>Delete</a><ul class='fa-ul text-left'><li><i class='fa-li fa fa-arrow-right'></i>Romantic Comedy<a href='/admin/edit_category/19'>Edit</a> <a onclick='return confirm(\"Are you sure?\");' href='/admin/delete-category/19'>Delete</a></li><li><i class='fa-li fa fa-arrow-right'></i>Romantic Drama<a href='/admin/edit_category/20'>Edit</a> <a onclick='return confirm(\"Are you sure?\");' href='/admin/delete-category/20'>Delete</a></li></ul></li></ul></li></ul>",

            [
                ["id" => 1, "parent_id" => null, "name" => "Electronics"],
                ["id" => 2, "parent_id" => null, "name" => "Toys"],
                ["id" => 3, "parent_id" => null, "name" => "Books"],
                ["id" => 4, "parent_id" => null, "name" => "Movies"],
                ["id" => 5, "parent_id" => 1, "name" => "Cameras"],
                ["id" => 6, "parent_id" => 1, "name" => "Computers"],
                ["id" => 7, "parent_id" => 1, "name" => "Cell Phones"],
                ["id" => 8, "parent_id" => 6, "name" => "Laptops"],
                ["id" => 9, "parent_id" => 6, "name" => "Desktop"],
                ["id" => 10, "parent_id" => 8, "name" => "Apple"],
                ["id" => 11, "parent_id" => 8, "name" => "Asus"],
                ["id" => 12, "parent_id" => 8, "name" => "Dell"],
                ["id" => 13, "parent_id" => 8, "name" => "Lenovo"],
                ["id" => 14, "parent_id" => 8, "name" => "HP"],
                ["id" => 15, "parent_id" => 3, "name" => "Children's Books"],
                ["id" => 16, "parent_id" => 3, "name" => "Kindle eBooks"],
                ["id" => 17, "parent_id" => 4, "name" => "Family"],
                ["id" => 18, "parent_id" => 4, "name" => "Romance"],
                ["id" => 19, "parent_id" => 18, "name" => "Romantic Comedy"],
                ["id" => 20, "parent_id" => 18, "name" => "Romantic Drama"]
            ]
        ];

    }
}

