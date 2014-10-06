<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

define('IBLOCK_ID', 16);
/*Группы доступа*/
$arPERM = Array( "1", "11", "12");
?>
<?/*формируем $arResult для поиска только в названиях*/
	if(!function_exists('onlyTitle')){
		function onlyTitle(&$arRes, $query){echo $query;
			$cnt = count($arRes);
			for($i=0; $i < $cnt; $i++){
				if(strpos($arRes[$i]['TITLE'], $query) === false){
					unset($arRes[$i]);
				}
			}
		}
	}
?>

<?/*формируем $arResult для поиска c фото*/
	if(!function_exists('withFoto')){
		function withFoto(&$arRes){
			$cnt = count($arRes);
			for($i=0; $i < $cnt; $i++){
				$db_elem = CIBlockElement::GetList(Array(), Array("ID"=>$arRes[$i]['ITEM_ID']));
				$arElem = $db_elem->Fetch();
				if(($arElem["PREVIEW_PICTURE"] || $arElem["DETAIL_PICTURE"]) === false){
					unset($arRes[$i]);
				}
			}
		}
	}
?>

<?/*Функция склонения*/
	if(!function_exists('inclination')){
		function inclination($time, $arr=array("отзыв","отзыва","отзывов")) {
			$timex = substr($time, -1);
			if ($time >= 10 && $time <= 20)
					return $arr[2];
			elseif ($timex == 1)
					return $arr[0];
			elseif ($timex > 1 && $timex < 5)
					return $arr[1];
			else
					return $arr[2];
	}
}
?>

<?/*Функция конвертации даты в название месяца и
	"сегодня", "вчера". Входящий параметр дата формат 02.10.2014 14:16*/
	if(!function_exists('dataToDay')){
		function dataToDay($time){
			$curDat = date('d.m.o');
			
			$arCur = explode('.',$curDat);
			$arTim = explode('.',$time);
			$hour = explode(' ', $arTim[2]);

			$intCur = intVal($arCur[0]);
			$intTim = intVal($arTim[0]);
			
			$rez = $intCur - $intTim;
			if($rez === 0 ){
				return 'сегодня '.$hour[1]; // сегодня 14:16
			}
			
			if($rez === 1){
				return 'вчера '.$hour[1];  // вчера 14:16
			}
			
			$arMonth = Array('01'=>'Января',
											 '02'=>'Февраля',
											 '03'=>'Марта',
											 '04'=>'Апреля',
											 '05'=>'Мая',
											 '06'=>'Июня',
											 '07'=>'Июля',
											 '08'=>'Августа',
											 '09'=>'Сентября',
											 '10'=>'Октября',
											 '11'=>'Ноября',
											 '12'=>'Декабря');
											 
			$time = $arTim[0].' '.$arMonth[$arTim[1]].' '.$hour[1];	// 28 Августа 13:08							 
			return $time;
		}
	}
?>

<?
	$findName = $_GET['findarticle'];
	$findFoto = $_GET['findWithFoto']; 
	
	if(!empty($_GET['findarticle'])){
		onlyTitle($arResult["SEARCH"], $_GET['q']);
	}
	
	if(!empty($_GET['findWithFoto'])){
		withFoto($arResult["SEARCH"]);
	}
?>

<?
	 $arGrpUsr = $USER->GetUserGroupArray();
	 $arRez = array_intersect($arGrpUsr, $arPERM);
	 if(empty($arGrpUsr)){
			echo "шаблон termodomAdvert\components\diva\search.page template.php arGrpUsr - пустой";
		}
?>

<div class="grid-12">
	<div class="block">
		<div class="breadcrumbs">
			<a class="brdcrmbs-home" href="/">
				<i class="fa fa-home"></i>
			</a>
			>
			<a href="/advert/">Объявления</a>
		</div>
		
		<h2>Результат поиска</h2>
		
	
		<?if($arParams["USE_SUGGEST"] === "Y"):
			if(strlen($arResult["REQUEST"]["~QUERY"]) && is_object($arResult["NAV_RESULT"]))
			{
				$arResult["FILTER_MD5"] = $arResult["NAV_RESULT"]->GetFilterMD5();
				$obSearchSuggest = new CSearchSuggest($arResult["FILTER_MD5"], $arResult["REQUEST"]["~QUERY"]);
				$obSearchSuggest->SetResultCount($arResult["NAV_RESULT"]->NavRecordCount);
			}
			?>
			<?$APPLICATION->IncludeComponent(
				"bitrix:search.suggest.input",
				"",
				array(
					"NAME" => "q",
					"VALUE" => $arResult["REQUEST"]["~QUERY"],
					"INPUT_SIZE" => 40,
					"DROPDOWN_SIZE" => 10,
					"FILTER_MD5" => $arResult["FILTER_MD5"],
				),
				$component, array("HIDE_ICONS" => "Y")
			);?>
		<?else:?>
			<!--<input type="text" name="q" value="<?/*=$arResult["REQUEST"]["QUERY"]*/?>" size="40" />-->
		<?endif;?>

		<?if($arParams["SHOW_WHERE"]):?>

				<?$APPLICATION->IncludeComponent(
					"diva:search.form",
					"flat",
					Array(
						"PAGE" => $APPLICATION->GetCurDir(),
						"IBLOCK_ID" => IBLOCK_ID
					),
					$component
				);?>

		<?endif;?>
			<!--&nbsp;<input type="submit" value="<?/*=GetMessage("SEARCH_GO")*/?>" />
			<input type="hidden" name="how" value="<?/*echo $arResult["REQUEST"]["HOW"]=="d"? "d": "r"*/?>" />-->
		<?if($arParams["SHOW_WHEN"]):?>
			<script>
			var switch_search_params = function()
			{
				var sp = document.getElementById('search_params');
				var flag;
				var i;

				if(sp.style.display == 'none')
				{
					flag = false;
					sp.style.display = 'block'
				}
				else
				{
					flag = true;
					sp.style.display = 'none';
				}

				var from = document.getElementsByName('from');
				for(i = 0; i < from.length; i++)
					if(from[i].type.toLowerCase() == 'text')
						from[i].disabled = flag;

				var to = document.getElementsByName('to');
				for(i = 0; i < to.length; i++)
					if(to[i].type.toLowerCase() == 'text')
						to[i].disabled = flag;

				return false;
			}
			</script>
			<a class="search-page-params" href="#" onclick="return switch_search_params()"><?echo GetMessage('CT_BSP_ADDITIONAL_PARAMS')?></a>
			<div id="search_params" class="search-page-params" style="display:<?echo $arResult["REQUEST"]["FROM"] || $arResult["REQUEST"]["TO"]? 'block': 'none'?>">
				<?$APPLICATION->IncludeComponent(
					'bitrix:main.calendar',
					'',
					array(
						'SHOW_INPUT' => 'Y',
						'INPUT_NAME' => 'from',
						'INPUT_VALUE' => $arResult["REQUEST"]["~FROM"],
						'INPUT_NAME_FINISH' => 'to',
						'INPUT_VALUE_FINISH' =>$arResult["REQUEST"]["~TO"],
						'INPUT_ADDITIONAL_ATTR' => 'size="10"',
					),
					null,
					array('HIDE_ICONS' => 'Y')
				);?>
			</div>
		<?endif?>
		
		<?if(!empty($arRez)):?>
			<div class="a-new">
				<strong>Хотите опубликовать свое объявление?</strong>
				<a class="btn green" href="">Подать объявление</a>
			</div>
		<?else:?>	
			<div class="a-new">
				<strong>
					Хотите опубликовать свое объявление?
					<a href="">Войдите</a>
					или
					<a href="">зарегистрируйтесь</a>
				</strong>
			</div>
		<?endif?>	

		<?if(isset($arResult["REQUEST"]["ORIGINAL_QUERY"])):
			?>
			<div class="search-language-guess">
				<?echo GetMessage("CT_BSP_KEYBOARD_WARNING", array("#query#"=>'<a href="'.$arResult["ORIGINAL_QUERY_URL"].'">'.$arResult["REQUEST"]["ORIGINAL_QUERY"].'</a>'))?>
			</div><?
		endif;?>

		<?if($arResult["REQUEST"]["QUERY"] === false && $arResult["REQUEST"]["TAGS"] === false):?>
		<?elseif($arResult["ERROR_CODE"]!=0):?>
			<p><?=GetMessage("SEARCH_ERROR")?></p>
			<?ShowError($arResult["ERROR_TEXT"]);?>
			<p><?=GetMessage("SEARCH_CORRECT_AND_CONTINUE")?></p>
			
			<p><?=GetMessage("SEARCH_SINTAX")?><b><?=GetMessage("SEARCH_LOGIC")?></b></p>
			<table border="0" cellpadding="5">
				<tr>
					<td align="center" valign="top"><?=GetMessage("SEARCH_OPERATOR")?></td><td valign="top"><?=GetMessage("SEARCH_SYNONIM")?></td>
					<td><?=GetMessage("SEARCH_DESCRIPTION")?></td>
				</tr>
				<tr>
					<td align="center" valign="top"><?=GetMessage("SEARCH_AND")?></td><td valign="top">and, &amp;, +</td>
					<td><?=GetMessage("SEARCH_AND_ALT")?></td>
				</tr>
				<tr>
					<td align="center" valign="top"><?=GetMessage("SEARCH_OR")?></td><td valign="top">or, |</td>
					<td><?=GetMessage("SEARCH_OR_ALT")?></td>
				</tr>
				<tr>
					<td align="center" valign="top"><?=GetMessage("SEARCH_NOT")?></td><td valign="top">not, ~</td>
					<td><?=GetMessage("SEARCH_NOT_ALT")?></td>
				</tr>
				<tr>
					<td align="center" valign="top">( )</td>
					<td valign="top">&nbsp;</td>
					<td><?=GetMessage("SEARCH_BRACKETS_ALT")?></td>
				</tr>
			</table>
		<?elseif(count($arResult["SEARCH"])>0):?>
			<?if($arParams["DISPLAY_TOP_PAGER"] != "N") echo $arResult["NAV_STRING"]?>

			<div class="companies-list clear">
				<?foreach($arResult["SEARCH"] as $arItem):?>
					<?
						$db_elem =CIBlockElement::GetByID($arItem["ITEM_ID"]);
						$arField = $db_elem->Fetch();
					?>
					
					<?/*Получаем путь к картинке*/
						$imgpath  = CFile::GetPath($arField['PREVIEW_PICTURE']);
					?>
					<div class="item">
						<div class="company-standalone-container clear light-gray">
						
							<?if($imgpath):?>	
								<div class="company-image">
									<a class="image-holder" style="background-image:url(<?=$imgpath?>);">
										<img src="<?=SITE_TEMPLATE_PATH?>/img/flats/empty-image.gif">
									</a>	
								</div>
							<?endif?>
							
							<div class="company-s-c-content">
									<span class="clist-date">
										<?=dataToDay($arField["ACTIVE_FROM"])?>
									</span>
									<h4>
										<a href="<?echo $arItem["URL"]?>"><?echo $arItem["TITLE_FORMATED"]?></a>
									</h4>	
									<?/*Достаем свойство цена*/
										$db_prop = CIBlockElement::GetProperty(IBLOCK_ID,
																													 $arItem["ITEM_ID"],
																													 Array(),
																													 Array("CODE"=>"PROP_PRICE"));
										$arProp = $db_prop->Fetch();
									?>
									<p class="clist-price">
										<?
												$price = preg_replace('/[^0-9]/i', '', $arProp['VALUE']);
										?>
										<?=$price?> руб.
									</p>
									<?if (
										$arParams["SHOW_RATING"] == "Y"
										&& strlen($arItem["RATING_TYPE_ID"]) > 0
										&& $arItem["RATING_ENTITY_ID"] > 0
									):?>
										<div class="search-item-rate"><?
											$APPLICATION->IncludeComponent(
												"bitrix:rating.vote", $arParams["RATING_TYPE"],
												Array(
													"ENTITY_TYPE_ID" => $arItem["RATING_TYPE_ID"],
													"ENTITY_ID" => $arItem["RATING_ENTITY_ID"],
													"OWNER_ID" => $arItem["USER_ID"],
													"USER_VOTE" => $arItem["RATING_USER_VOTE_VALUE"],
													"USER_HAS_VOTED" => $arItem["RATING_USER_VOTE_VALUE"] == 0? 'N': 'Y',
													"TOTAL_VOTES" => $arItem["RATING_TOTAL_VOTES"],
													"TOTAL_POSITIVE_VOTES" => $arItem["RATING_TOTAL_POSITIVE_VOTES"],
													"TOTAL_NEGATIVE_VOTES" => $arItem["RATING_TOTAL_NEGATIVE_VOTES"],
													"TOTAL_VALUE" => $arItem["RATING_TOTAL_VALUE"],
													"PATH_TO_USER_PROFILE" => $arParams["~PATH_TO_USER_PROFILE"],
												),
												$component,
												array("HIDE_ICONS" => "Y")
											);?>
										</div>
									<?endif;?>
									<ul class="company-s-c-stats">
										<li class="add-to-favorite">
											<a>
												<i class="fa fa-star-o"> </i>
												<span class="already-in-text">Уже в избранном</span>
												<span class="add-to-favorite-text">Добавить в избранное</span>
											</a>
										</li>
										<li>
											<i class="fa fa-eye"></i>
											<a href="">
												<?=$arField["SHOW_COUNTER"]?> 
												<?=inclination($arField["SHOW_COUNTER"], Array('просмотр',
																																			 'просмотра',
																																			 'просмотров')
																																			 );?>
											</a>
										</li>
									</ul>
							</div><!--class="company-s-c-content"-->	
							
						</div><!--class="company-standalone-container clear light-gray"-->
					</div><!--class="item"-->	
				<?endforeach;?>
			</div><!--class="companies-list clear"-->	
			<?if($arParams["DISPLAY_BOTTOM_PAGER"] != "N") echo $arResult["NAV_STRING"]?>

			<p>
			<?if($arResult["REQUEST"]["HOW"]=="d"):?>
				<a href="<?=$arResult["URL"]?>&amp;how=r<?echo $arResult["REQUEST"]["FROM"]? '&amp;from='.$arResult["REQUEST"]["FROM"]: ''?><?echo $arResult["REQUEST"]["TO"]? '&amp;to='.$arResult["REQUEST"]["TO"]: ''?>"><?=GetMessage("SEARCH_SORT_BY_RANK")?></a>&nbsp;|&nbsp;<b><?=GetMessage("SEARCH_SORTED_BY_DATE")?></b>
			<?else:?>
				<b><?=GetMessage("SEARCH_SORTED_BY_RANK")?></b>&nbsp;|&nbsp;<a href="<?=$arResult["URL"]?>&amp;how=d<?echo $arResult["REQUEST"]["FROM"]? '&amp;from='.$arResult["REQUEST"]["FROM"]: ''?><?echo $arResult["REQUEST"]["TO"]? '&amp;to='.$arResult["REQUEST"]["TO"]: ''?>"><?=GetMessage("SEARCH_SORT_BY_DATE")?></a>
			<?endif;?>
			</p>
		<?else:?>
			<?ShowNote(GetMessage("SEARCH_NOTHING_TO_FOUND"));?>
		<?endif;?>
	</div><!--class="block"-->
</div><!--class="grid-12"-->
<div class="clear"></div>