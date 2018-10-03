<?
/**
 * Своего рода инкапсуляция методов работы с highload-блоками
 * битрикса. Все методы статические.
 *
 * @package Bitrix-Highload
 * @version 0.1
 * @author  Vadim A. Nikitin <nikitin.vadim@gmail.com>
 *
 * @example HLI::Add(1, array("UF_STR" => "Test"));
 * @example HLI::Update(1, 1, array("UF_STR" => "NewTest"));
 * @example HLI::GetList(1, array(">UF_NUMBER" => 10), array("*"), array("ID" => "DESC"));
 * @example HLI::Delete(1, 10);
 */
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Loader;
use Bitrix\Main\Entity;
use Bitrix\Highloadblock as HL;

if(!Loader::includeModule("highloadblock"))
{
	ShowError("Module \"HighloadBlock\" not installed");
	die();
}

/**
 * Class HLI
 */
final class HLI
{

    /**
     * Add to highload-block
     * @param int   $hlblock_id
     * @param array $data
     * @return array|\Bitrix\Main\ORM\EntityError[]|\Bitrix\Main\ORM\Fields\FieldError[]|int
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
	public static function Add($hlblock_id = 0, $data = array())
	{
		$hlblock = HL\HighloadBlockTable::getById($hlblock_id)->fetch();
		$entity  = HL\HighloadBlockTable::compileEntity($hlblock);
		$entity_data_class = $entity->getDataClass();

		$result = $entity_data_class::add($data);

		if($result->isSuccess())
		    return $result->getId();
		else
		    return $result->getErrors();
	}

    /**
     * Update data
     * @param int   $hlblock_id
     * @param int   $id
     * @param array $data
     * @return bool
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
	public static function Update($hlblock_id = 0, $id = 0, $data = array())
	{
		$hlblock = HL\HighloadBlockTable::getById($hlblock_id)->fetch();
		$entity  = HL\HighloadBlockTable::compileEntity($hlblock);
		$entity_data_class = $entity->getDataClass();

		$result = $entity_data_class::update($id, $data);
		return $result->isSuccess();
	}

    /**
     * Load list by params
     * @param int   $hlblock_id
     * @param array $filter
     * @param array $select
     * @param array $order
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
	public static function GetList($hlblock_id = 0, $filter = array(), $select = array(), $order = array())
	{

		$arResult = array();
		$arSelect = array();
		$arFilter = array();
		$arOrder  = array();

		if(empty($select))
			$arSelect = array("*");

		if(empty($filter))
			$arFilter = array();

		if(empty($order))
			$arOrder = array("ID" => "DESC");

		$hlblock = HL\HighloadBlockTable::getById($hlblock_id)->fetch();
		$entity  = HL\HighloadBlockTable::compileEntity($hlblock);
		$entity_data_class = $entity->getDataClass();

		$rsData = $entity_data_class::getList(
			array(
				"select" => $arSelect,
				"order"  => $arOrder,
				"filter" => $arFilter
			)
		);

		while($arData = $rsData->Fetch()){
			$arResult[] = $arData;
		}
		return $arResult;
	}

    /**
     * Delete row by ID
     * @param int $hlblock_id
     * @param int $id
     * @return \Bitrix\Main\ORM\Data\DeleteResult
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
	public static function Delete($hlblock_id = 0, $id = 0)
	{
		$hlblock = HL\HighloadBlockTable::getById($hlblock_id)->fetch();
		$entity  = HL\HighloadBlockTable::compileEntity($hlblock);
		$entity_data_class = $entity->getDataClass();

		$result = $entity_data_class::delete($id);

		return $result;
	}
};
