
<?php
    //menu items ophalen
    $sqlMenu = $mysqli -> prepare("SELECT id, item1, paginaurl, externeurl, targetlink FROM siteworkcms WHERE in_menu = ? AND hoofdid = '0' AND STATUS = 'actief' ORDER BY menu_volgorde") OR DIE ($mysqli->error.__LINE__);
    $like = "1";
    $sqlMenu -> bind_param('i',$like);
    $sqlMenu -> execute();
    $sqlMenu -> store_result();
	$sqlMenu -> bind_result($idMenu, $item1Menu, $paginaurlMenu, $externeurlMenu, $targetlinkMenu);
?>

<nav id="navigation1" class="navigation">
	
	<div class="nav-header">
		<div class="nav-toggle"></div>
	</div>

	<div class="nav-menus-wrapper">
		<div class="sectie-inner sectie-inner-wide">
			<ul class="nav-menu">

			<?php 
			while($sqlMenu->fetch()) 
			{
					// Taal menu
					$paginaurlMenu = the_field('paginaurl', $idMenu);
					$externeurlMenu = the_field('externeurl', $idMenu);
					$targetlinkMenu = the_field('targetlink', $idMenu);
					$item1Menu = the_field('item1', $idMenu);

					//link opbouwen van menu item
					if ( $externeurlMenu ) 
					{
						$link = $externeurlMenu;
					} 
					else 
					{
						$link = get_link($idMenu);
					}

					//active status
					if ( $idMenu == get_id() OR  $idMenu == the_field('hoofdid')) 
					{
						$active = 'active';
					}
					else 
					{
						$active = '';
					}

					//target blank
					if ( $externeurlMenu <> "" && $targetlinkMenu == "ja") 
					{ 
						$target = '_blank'; 
					}
					else 
					{
						$target = ''; 
					}

					/*submenu ophalen*/
					$sqlSubMenu = $mysqli -> prepare("SELECT id, item1, paginaurl, externeurl, targetlink FROM siteworkcms WHERE hoofdid = ? AND STATUS = 'actief' ORDER BY menu_volgorde") OR DIE ($mysqli->error.__LINE__);
					$sqlSubMenu -> bind_param('s',$idMenu);
					$sqlSubMenu -> execute();
					$sqlSubMenu -> store_result();
					$sqlSubMenu -> bind_result($idSubMenu, $item1SubMenu, $paginaurlSubMenu, $externeurlSubMenu, $targetlinkSubMenu);

				if($item1Menu <> ""):
					echo "
					<li class=\"{$active}\"> 
						<a href=\"{$link}\" target=\"{$target}\">
							{$item1Menu}
						</a>
					";

					if ($sqlSubMenu->num_rows > 0) 
					{
						$subOpbouw = 1;
							while( $sqlSubMenu ->fetch() )
							{
								// Taal menu	
								$paginaurlSubMenu = the_field('paginaurl', $idSubMenu);
								$externeurlSubMenu = the_field('externeurl', $idSubMenu);
								$targetlinkSubMenu = the_field('targetlink', $idSubMenu);
								$item1SubMenu = the_field('item1', $idSubMenu);

								if($subOpbouw == 1 && $item1SubMenu <> "") {
									echo "<ul class=\"nav-dropdown\">";
								}

								//link opbouwen van menu item
								if ( $externeurlSubMenu ) 
								{
									$linkSubMenu = $externeurlSubMenu;
								} 
								else 
								{
									$linkSubMenu = get_link($idSubMenu);
								}

								//active status
								if ( $idSubMenu == get_id() ) 
								{
									$activeSub = 'active';
								}
								else 
								{
									$activeSub = '';
								}

								//target blank
								if ( $externeurlSubMenu <> "" && $targetlinkSubMenu == "ja") 
								{ 
									$targetSub = '_blank'; 
								}
								else 
								{
									$targetSub = ''; 
								}

								if($item1SubMenu <> ""):
									echo "
									<li class=\"{$activeSub}\">
										<a href=\"{$linkSubMenu}\" target=\"{$targetSub}\">{$item1SubMenu}</a>
									</li>
									";
								endif;

								if($subOpbouw == $sqlSubMenu->num_rows) {
									echo "</ul>";
								}

								$subOpbouw++;
							}
					}
				echo "
				</li>
				";
				endif;
			}
			?>
			</ul>
		</div>
	</div>
</nav>
