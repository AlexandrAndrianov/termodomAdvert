<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="big-search raised-corners green">
	<form action="<?=$arResult["FORM_ACTION"]?>">
		<div class="big-search-main clear">
		
			<div class="bsm-f-s">
				<div class="big-search-field-holder">
					<input class="big-search-field" placeholder="Искать" type="text" name="q" 
								value="" size="15"  maxlength="50" />
				</div>
			</div>
			<pre><?print_r($arResult)?></pre>
			<?


				  // выборка только активных разделов из инфоблока $IBLOCK_ID, в которых есть элементы 
				  // со значением свойства SRC, начинающееся с https://
				  $arFilter = Array('IBLOCK_ID'=>$IBLOCK_ID, Array(), Array());
				  $db_list = CIBlockSection::GetList(Array($by=>$order), $arFilter, true);
				  $db_list->NavStart(20);
				  echo $db_list->NavPrint($arIBTYPE["SECTION_NAME"]);
				  while($ar_result = $db_list->GetNext())
				  {
					echo $ar_result['ID'].' '.$ar_result['NAME'].': '.$ar_result['ELEMENT_CNT'].'<br>';
				  }
				  echo $db_list->NavPrint($arIBTYPE["SECTION_NAME"]);
				
			?>
			<div class="bsm-s">
				<div class="big-search-field-holder">
					<select class="big-search-select" name="kategory">
						<option value="9">Автомобили с пробегом</option>
						<option value="109">Новые автомобили</option>
						<option value="14">Мотоциклы и мототехника</option>
						<option value="81">Грузовики и спецтехника</option>
						<option value="11">Водный транспорт</option>
						<option value="10">Запчасти и аксессуары</option>
					</select>
				</div>
			</div>
			
			<div class="bsm-b">		
				<input class="big-search-submit gray" name="s" type="submit" 
							value="<?=GetMessage("BSF_T_SEARCH_BUTTON");?>" />
			</div>
			
		</div>
	</form>
</div>