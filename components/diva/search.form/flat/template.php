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
			
			
			<div class="bsm-s">
				<div class="big-search-field-holder">
					<select class="big-search-select" name="kategory">
						<option selected="true" disabled="disabled">
							Везде
						</option>
						<?
							  $arFilter = Array('IBLOCK_ID'=>$arParams['IBLOCK_ID']);
							  $db_list = CIBlockSection::GetList(Array("left_margin"=>"asc"), $arFilter);

							  while($ar_result = $db_list->GetNext())
							  {
								if(empty($ar_result['IBLOCK_SECTION_ID']))
								{
						?>		
									<option value="<?=$ar_result['ID']?>" style="font-weight: bold;">
										<?=$ar_result['NAME']?>
									</option>
						<?			
								}
								else
								{
						?>		
									<option value="<?=$ar_result['ID']?>">
										<?/*if($ar_result['DEPTH_LEVEL']>2):?>
											-
										<?endif*/?>
										<?=$ar_result['NAME']?>
									</option>
						<?			
								}
							  }
						?>
						
					</select>
				</div>
			</div>
			
			<div class="bsm-b">		
				<input class="big-search-submit gray" name="s" type="submit" 
							value="<?=GetMessage("BSF_T_SEARCH_BUTTON");?>" />
			</div>
			
		</div><!--big-search-main clear-->
		
		<div class="big-search-accurate">
			<label>
				<input name="findarticle" class="accurate-chb" type="checkbox" value="article">
					искать только в названиях
			</label>
			<label>
				<input name="findWithFoto" class="accurate-chb" type="checkbox" value="WithFoto">
					с фото
			</label>
		</div><!--big-search-accurate-->
		
	</form>
</div>