<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?/*Навигационная цепочка для вложенных категорий*/
	if(!function_exists('breadcrumb'))	
	{
		function breadcrumb($parent_id, $folder)
		{
			$res_par = CIBlockSection::GetByID($parent_id);
			$ar_res_par = $res_par->Fetch();
			if(!empty($ar_res_par['IBLOCK_SECTION_ID']))
			{
				breadcrumb($ar_res_par['IBLOCK_SECTION_ID'], $folder);
				echo ' > <a href="'.$folder.$ar_res_par['ID'].'/">'.$ar_res_par['NAME'].'</a>';
			}
			else
			{
				echo ' > <a href="'.$folder.$ar_res_par['ID'].'/">'.$ar_res_par['NAME'].'</a>';
			}
		}
	}
?>

<div class="grid-12">
		<div class="block">
		
			<?
				$res = CIBlockSection::GetByID($arResult['VARIABLES']['SECTION_ID']);
				$ar_res = $res->Fetch();
				
			?>
			
			<div class="breadcrumbs">
				<a class="brdcrmbs-home" href="/">
					<i class="fa fa-home"></i>
				</a>
				>
				<a href="<?=$arResult["FOLDER"]?>">Объявления</a>
				
				<?if(!empty($ar_res['IBLOCK_SECTION_ID'])):?>
						<?breadcrumb($ar_res['IBLOCK_SECTION_ID'], $arParams['SEF_FOLDER']);?>
				<?endif?>
			</div>
			
			<h2>Объявления 
				<?if($ar_res['NAME']):?>
					<?echo '&laquo;'.$ar_res['NAME'].'&raquo;';?>
				<?endif?>
			</h2>
				<?if($arParams["USE_RSS"]=="Y"):?>
					<?
					$rss_url = CComponentEngine::makePathFromTemplate($arResult["FOLDER"].$arResult["URL_TEMPLATES"]["rss_section"], $arResult["VARIABLES"]);
					if(method_exists($APPLICATION, 'addheadstring'))
						$APPLICATION->AddHeadString('<link rel="alternate" type="application/rss+xml" title="'.$rss_url.'" href="'.$rss_url.'" />');
					?>
					<a href="<?=$rss_url?>" title="rss" target="_self"><img alt="RSS" src="<?=$templateFolder?>/images/gif-light/feed-icon-16x16.gif" border="0" align="right" /></a>
				<?endif?>

				<?if($arParams["USE_SEARCH"]=="Y"):?>
					<?$APPLICATION->IncludeComponent(
						"diva:search.form",
						"flat",
						Array(
							"PAGE" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["search"],
							"IBLOCK_ID" => $arParams['IBLOCK_ID']
						),
						$component
					);?>
				<?endif?>
				
				<?
				  $arFilter = Array('IBLOCK_ID'=>$arParams['IBLOCK_ID'], 
											'SECTION_ID' => $arResult['VARIABLES']['SECTION_ID']);
				  $db_list = CIBlockSection::GetList(Array("left_margin"=>"asc"), $arFilter, true);
				  $db_list->NavStart();
				 ?>

				 <?if($db_list->NavRecordCount):?>	
						<div class="raised-corners ccl">
							<h3>Категории</h3>
							<ul class="companies-cat-list col3 clear">
							<?
							  while($ar_result = $db_list->GetNext())
							  {
							 ?>
								<li class="item">
									<a href="<?=$arResult["FOLDER"].$ar_result['ID'].'/';?>">
										<span><?=$ar_result['NAME']?></span>
									</a>
									<span class="companies-cat-count">(<?=$ar_result['ELEMENT_CNT']?>)</span>
								</li>
							<?
								}
							?>	
							</ul>
						</div>
				<?endif?>	

				<?if($arParams["USE_FILTER"]=="Y"):?>
				<?$APPLICATION->IncludeComponent(
					"bitrix:catalog.filter",
					"",
					Array(
						"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
						"IBLOCK_ID" => $arParams["IBLOCK_ID"],
						"FILTER_NAME" => $arParams["FILTER_NAME"],
						"FIELD_CODE" => $arParams["FILTER_FIELD_CODE"],
						"PROPERTY_CODE" => $arParams["FILTER_PROPERTY_CODE"],
						"CACHE_TYPE" => $arParams["CACHE_TYPE"],
						"CACHE_TIME" => $arParams["CACHE_TIME"],
						"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
					),
					$component
				);
				?>

				<?endif?>
				<?$APPLICATION->IncludeComponent(
					"bitrix:news.list",
					"",
					Array(
						"IBLOCK_TYPE"	=>	$arParams["IBLOCK_TYPE"],
						"IBLOCK_ID"	=>	$arParams["IBLOCK_ID"],
						"NEWS_COUNT"	=>	$arParams["NEWS_COUNT"],
						"SORT_BY1"	=>	$arParams["SORT_BY1"],
						"SORT_ORDER1"	=>	$arParams["SORT_ORDER1"],
						"SORT_BY2"	=>	$arParams["SORT_BY2"],
						"SORT_ORDER2"	=>	$arParams["SORT_ORDER2"],
						"FIELD_CODE"	=>	$arParams["LIST_FIELD_CODE"],
						"PROPERTY_CODE"	=>	$arParams["LIST_PROPERTY_CODE"],
						"DISPLAY_PANEL"	=>	$arParams["DISPLAY_PANEL"],
						"SET_TITLE"	=>	$arParams["SET_TITLE"],
						"SET_STATUS_404" => $arParams["SET_STATUS_404"],
						"INCLUDE_IBLOCK_INTO_CHAIN"	=>	$arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
						"ADD_SECTIONS_CHAIN"	=>	$arParams["ADD_SECTIONS_CHAIN"],
						"CACHE_TYPE"	=>	$arParams["CACHE_TYPE"],
						"CACHE_TIME"	=>	$arParams["CACHE_TIME"],
						"CACHE_FILTER"	=>	$arParams["CACHE_FILTER"],
						"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
						"DISPLAY_TOP_PAGER"	=>	$arParams["DISPLAY_TOP_PAGER"],
						"DISPLAY_BOTTOM_PAGER"	=>	$arParams["DISPLAY_BOTTOM_PAGER"],
						"PAGER_TITLE"	=>	$arParams["PAGER_TITLE"],
						"PAGER_TEMPLATE"	=>	$arParams["PAGER_TEMPLATE"],
						"PAGER_SHOW_ALWAYS"	=>	$arParams["PAGER_SHOW_ALWAYS"],
						"PAGER_DESC_NUMBERING"	=>	$arParams["PAGER_DESC_NUMBERING"],
						"PAGER_DESC_NUMBERING_CACHE_TIME"	=>	$arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
						"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
						"DISPLAY_DATE"	=>	$arParams["DISPLAY_DATE"],
						"DISPLAY_NAME"	=>	"Y",
						"DISPLAY_PICTURE"	=>	$arParams["DISPLAY_PICTURE"],
						"DISPLAY_PREVIEW_TEXT"	=>	$arParams["DISPLAY_PREVIEW_TEXT"],
						"PREVIEW_TRUNCATE_LEN"	=>	$arParams["PREVIEW_TRUNCATE_LEN"],
						"ACTIVE_DATE_FORMAT"	=>	$arParams["LIST_ACTIVE_DATE_FORMAT"],
						"USE_PERMISSIONS"	=>	$arParams["USE_PERMISSIONS"],
						"GROUP_PERMISSIONS"	=>	$arParams["GROUP_PERMISSIONS"],
						"FILTER_NAME"	=>	$arParams["FILTER_NAME"],
						"HIDE_LINK_WHEN_NO_DETAIL"	=>	$arParams["HIDE_LINK_WHEN_NO_DETAIL"],
						"CHECK_DATES"	=>	$arParams["CHECK_DATES"],

						"PARENT_SECTION"	=>	$arResult["VARIABLES"]["SECTION_ID"],
						"PARENT_SECTION_CODE"	=>	$arResult["VARIABLES"]["SECTION_CODE"],
						"DETAIL_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
						"SECTION_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
						"IBLOCK_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
					),
					$component
				);?>
		</div><!--block-->
</div><!--grid-12-->
<div class="clear"></div>