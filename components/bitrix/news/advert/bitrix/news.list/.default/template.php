<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?/*Функция склонения*/
	if(!function_exists('inclination')){
		function inclination($time, $arr=array("отзыв","отзыва","отзывов")){
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

<?/*Формируем путь раздела*/
	if(!function_exists('pathSect')){
		function pathSect($path){
			for($cnt=0,$len = strLen($path); true; $len--){
				if($cnt == 2) break;
				if($path[$len] === '/') ++$cnt;
				$path[$len] = "\n";
			}
			return $path.'/';
		}
	}
?>

<?/*Функция конвертации даты в название месяца
	сегодня, вчера. Входящий параметр дата формат 02.10.2014 14:16*/
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
				return 'сегодня '.$hour[1]; 
			}
			
			if($rez === 1){
				return 'вчера '.$hour[1];
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
											 
			$time = $arTim[0].' '.$arMonth[$arTim[1]].' '.$hour[1];								 
			return $time;
		}
	}
?>
				
				<?if($arParams["DISPLAY_TOP_PAGER"]):?>
					<?=$arResult["NAV_STRING"]?>
				<?endif;?>
				<div class="companies-list clear">
						<?foreach($arResult["ITEMS"] as $arItem):?>
							<div class="item">
								<div class="company-standalone-container clear light-gray"
											id="<?=$this->GetEditAreaId($arItem['ID']);?>">
									<?
									$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
									$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
									?>
									<div class="company-image">
										<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
											<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
												<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="image-holder" 
														style="background-image:url(<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>);">
													<img src="/advert/images/empty-image.gif" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>"/>
												</a>
											<?else:?>
												<img class="preview_picture" border="0" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>" height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" style="float:left" />
											<?endif;?>
										<?endif?>
									</div>
									<div class="company-s-c-content">
								
										<?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
											<span class="clist-date"><?=dataToDay($arItem["DISPLAY_ACTIVE_FROM"])?></span>
										<?endif?>
										<?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
											<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
												<h4>	
													<a href="<?echo $arItem["DETAIL_PAGE_URL"]?>"><?echo $arItem["NAME"]?></a>
												</h4>	
											<?else:?>
												<b><?echo $arItem["NAME"]?></b>
											<?endif;?>
										<?endif;?>
										<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
											<?echo $arItem["PREVIEW_TEXT"];?>
										<?endif;?>
										<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
											<div style="clear:both"></div>
										<?endif?>
										<p class="clist-price">
											<?
												$price = preg_replace('/[^0-9]/i', '',
																							$arItem["DISPLAY_PROPERTIES"]["PROP_PRICE"]["VALUE"]);
											?>
											<?=$price?> руб.
										</p>
										
										<p class="company-s-c-cat">
											
											<a href="<?=pathSect($arItem["DETAIL_PAGE_URL"])?>">
												<?$db_sect = CIBlockSection::GetByID($arItem["IBLOCK_SECTION_ID"]);
													$arSect = $db_sect->Fetch();
												?>
												<?=$arSect["NAME"]?>
											</a>
										</p>

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
													<?if(!empty($arItem["FIELDS"]["SHOW_COUNTER"])){
														$cnt = $arItem["FIELDS"]["SHOW_COUNTER"];
														}else{
															$cnt = 0;
														}
													?>
													<?=$cnt?> <?=inclination($cnt, $arr=array("показ","показа","показов"))?>
												</a>
											</li>
										</ul>
										
									</div><!-- class="company-s-c-content" -->	
								</div><!-- class="company-standalone-container clear light-gray"-->	
							</div><!--class="item"-->	
						<?endforeach;?>
				</div><!--class="companies-list clear"-->		
				<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
					<?=$arResult["NAV_STRING"]?>
				<?endif;?>
		

