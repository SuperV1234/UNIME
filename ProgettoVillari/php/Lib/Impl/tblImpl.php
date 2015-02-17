<?php

class TblUser extends Tbl
{
	public function mkUser()
	{

	}

	public function findByCredentials($mUser, $mPass)
	{
		$hash = Utils::getPwdHash($mPass);
		return $this->getFirstWhere('username = '.DB::v($mUser).' AND password_hash = '.DB::v($hash));
	}
}

class TblGroup extends Tbl
{
	public function mkGroup($mIDParent, $mName, $mPrivs, &$mMsg)
	{
		if(!Utils::checkEmptyStr($mName, $mMsg)) return false;

		$parentId = Utils::getInsertParent($this, $mIDParent, $mMsg);
		if(!$parentId) return false;

		$this->insert($parentId, $mName, DB::v($mPrivs->toStr()));
		$mMsg = "Group created successfully.";
		return true;
	}

	public function getHierarchyStr()
	{	
		$res = "";

		$this->forChildren(function(&$mRow, $mDepth) use (&$res)
		{
			$indent = str_repeat("--->", $mDepth);

			$id = $mRow['id'];
			$name = $mRow['name'];
			$privileges = $mRow['privileges'];

			$res .= $indent . "($id) $name [$privileges]\n";
		});

		return $res;
	}
}

class TblSection extends Tbl
{
	public function getHierarchyStr()
	{	
		$res = "";

		$this->forChildren(function(&$mRow, $mDepth) use (&$res)
		{
			$indent = str_repeat("--->", $mDepth);

			$id = $mRow['id'];
			$name = $mRow['name'];

			$res .= $indent . "($id) $name\n";
		});

		return $res;
	}
}

class TblGroupSectionPermission extends Tbl
{

}

class TblCData extends Tbl
{
	public function createCDataAndGetID()
	{
		$idAuthor = Credentials::getCUID();
		$res = $this->insertValues(date('Y-m-d'), $idAuthor);

		if(!$res) return null;
		return DB::getInsertedID();
	}
}

class TblThread extends Tbl
{
	public function mkThreadAndCData($mIDSection, $mTitle)
	{
		$cdID = TBS::$cdata->createCDataAndGetID();
		$res = $this->insertValues($cdID, $mIDSection, $mTitle);

		return $res;
	}
}

class TBS
{
	public static $section;
	public static $group;
	public static $user;
	public static $gsperms;
	public static $cdata;
	public static $thread;
}

TBS::$section = new TblSection('tbl_section');
TBS::$section->setInsertFields('id_parent', 'name');

TBS::$group = new TblGroup('tbl_group');
TBS::$group->setInsertFields('id_parent', 'name', 'privileges');

TBS::$user = new TblUser('tbl_user');
TBS::$user->setInsertFields('id_group', 'username', 'password_hash', 'email', 'registration_date', 'firstname', 'lastname', 'birth_date');

TBS::$gsperms = new TblGroupSectionPermission('tbl_group_section_permission');
TBS::$gsperms->setInsertFields('id_group', 'id_section', 'can_view', 'can_post', 'can_create_thread', 'can_delete_post', 'can_delete_thread', 'can_delete_section');

TBS::$cdata = new TblCData('tbl_creation_data');
TBS::$cdata->setInsertFields('creation_date', 'id_author');

TBS::$thread = new TblThread('tbl_thread');
TBS::$thread->setInsertFields('id_creation_data', 'id_section', 'title');

?>