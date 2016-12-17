<?php
class servicesAPI {
    /**
     * This method returns a string
     *
     * @param String $value
     * @return String
     */
    // define filters and validators for input
    private $_filters = array(
        'title'     => array('HtmlEntities', 'StripTags', 'StringTrim'),
        'shortdesc' => array('HtmlEntities', 'StripTags', 'StringTrim'),
        'price'     => array('HtmlEntities', 'StripTags', 'StringTrim'),
        'quantity'  => array('HtmlEntities', 'StripTags', 'StringTrim')
    );

    private $_validators = array(
        'title'     => array(),
        'shortdesc' => array(),
        'price'     => array('Float'),
        'quantity'  => array('Int')
    );

    /**
     * Returns list of all products in database
     *
     * @return array
     */
    public function getProducts()
    {

        $p = array(
            'title'     => 'Spinning Top',
            'shortdesc' => 'Hours of fun await with this colorful spinning top.
      Includes flashing colored lights.',
            'price'     => '3.99',
            'quantity'  => 57
        );
        return $p;

    }

    /**
     * Returns specified product in database
     *
     * @param integer $id
     * @return array|Example_Exception
     */
    public function getProduct($id)
    {
        $content = "some text here".$id;
        $fp = fopen("myText.txt","wb");
        fwrite($fp,$content);
        fclose($fp);

        return $id;
    }

    /**
     * Adds new product to database
     *
     * @param array $data array of data values with keys -> table fields
     * @return integer id of inserted product
     */
    public function addProduct($data)
    {

    }

    /**
     * Deletes product from database
     *
     * @param integer $id
     * @return integer number of products deleted
     */
    public function deleteProduct($id)
    {

    }

    /**
     * Updates product in database
     *
     * @param integer $id
     * @param array $data
     * @return integer number of products updated
     */
    public function updateProduct($id, $data)
    {

    }

}
