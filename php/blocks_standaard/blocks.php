<?php
if(get_taal() != 'nl') {
	$sqlBlock = $mysqli -> prepare("SELECT id, block_id, veld, waarde FROM sitework_vertaling_blocks WHERE cms_id = ? AND hoofdid = '0' $taalquery ORDER BY volgorde ASC ") or die ($mysqli->error.__LINE__);
	$pageId = get_id();
	$sqlBlock -> bind_param('i',$pageId);
	$sqlBlock -> execute();
	$sqlBlock->store_result();
	$sqlBlock->bind_result($idBlock, $blockidBlock, $tekstBlock, $tekstWaardeBlock);
	while($sqlBlock->fetch()) {
		$_GET['block_id'] = $idBlock;
		$blockId = $_GET['block_id'];
		//haal bestandsnaam van gekoppeld blok op en maak de include
		$sqlBlockInfo = $mysqli -> prepare("SELECT bestandsnaam FROM sitework_block WHERE actief = '1' AND id = ?") or die ($mysqli->error.__LINE__);
		$sqlBlockInfo -> bind_param('i',$blockidBlock);
		$sqlBlockInfo -> execute();
		$sqlBlockInfo->store_result();
		$sqlBlockInfo->bind_result($blockBestandsnaam);
		$sqlBlockInfo -> fetch();

		if($blockBestandsnaam != '') {
			include('php/blocks/'.$blockBestandsnaam.'.php');
		}
		$blockBestandsnaam = "";
	}
} else {
	$sqlBlock = $mysqli -> prepare("SELECT id, block_id, tekst FROM sitework_blocks WHERE cms_id = ? ORDER BY volgorde ASC ") or die ($mysqli->error.__LINE__);
	$pageId = get_id();
	$sqlBlock -> bind_param('i',$pageId);
	$sqlBlock -> execute();
	$sqlBlock->store_result();
	$sqlBlock->bind_result($idBlock, $blockidBlock, $tekstBlock);
	while($sqlBlock->fetch()) {
		$_GET['block_id'] = $idBlock;
		$blockId = $_GET['block_id'];
		//haal bestandsnaam van gekoppeld blok op en maak de include
		$sqlBlockInfo = $mysqli -> prepare("SELECT bestandsnaam FROM sitework_block WHERE actief = '1' AND id = ?") or die ($mysqli->error.__LINE__);
		$sqlBlockInfo -> bind_param('i',$blockidBlock);
		$sqlBlockInfo -> execute();
		$sqlBlockInfo->store_result();
		$sqlBlockInfo->bind_result($blockBestandsnaam);
		$sqlBlockInfo -> fetch();

		if($blockBestandsnaam != '') {
			include('php/blocks/'.$blockBestandsnaam.'.php');
		}
		$blockBestandsnaam = "";
	}
}

?>
