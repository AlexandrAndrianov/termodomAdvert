<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
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

<?/*Название секции для хлебных крошек*/
	$pathSect = count($arResult["SECTION"]["PATH"]);
?>

	<div class="grid-12">
		<div class="block">
			<div class="breadcrumbs">
				<a class="brdcrmbs-home" href="/">
					<i class="fa fa-home"></i>
				</a>
				>
				<a href="/advert/">Объявления</a>
				>
				<a href="<?=$arResult["SECTION"]["PATH"][$pathSect-1]["SECTION_PAGE_URL"]?>">
					<?=$arResult["SECTION"]["PATH"][$pathSect-1]["NAME"]?>
				</a>
			</div>
			<h2><?=$arResult["NAME"]?></h2>
		</div>
	</div>
	<div class="clear"></div>
</div>


<div class="container">
	<div class="grid-8">
		<div class="block">
			<div class="a-inside">
				<div class="a-image">
					<div class="a-image-noslider raised-corners">
						<div class="valigner">
							<div class="valigner-row">
								<div class="valigner-cell">
									<a href="">
										<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arResult["DETAIL_PICTURE"])):?>
											<img class="detail_picture" border="0" src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" width="<?=$arResult["DETAIL_PICTURE"]["WIDTH"]?>" height="<?=$arResult["DETAIL_PICTURE"]["HEIGHT"]?>" alt="<?=$arResult["NAME"]?>"  title="<?=$arResult["NAME"]?>" />
										<?endif?>
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="clear"></div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="grid-4">
		<div class="block">
			<div class="a-inside">
				<ul class="a-stats">
					<li>
						<i class="fa fa-clock-o"></i>
							<?
								$arDate = explode(' ', $arResult["ACTIVE_FROM"]);
							?>
							Размещено <?=$arDate["0"]?> в <?=$arDate["1"]?>
					</li>
					<li>
						<i class="fa fa-eye"></i>
							<?=$arResult["SHOW_COUNTER"]?> 
							<?=inclination($arResult["SHOW_COUNTER"], Array("просмотр",
																															"просмотра",
																															"просмотров"))?>
					</li>
				</ul>
				<ul class="a-options">
					<li class="a-price">
						<div class="names">
							<span>Цена: </span>
						</div>
						<div class="contents">
							<?
								$price = preg_replace('/[^0-9]/i', '',
																			$arResult["PROPERTIES"]["PROP_PRICE"]["VALUE"]);
							?>
							<strong><?=$price?> р.</strong>
						</div>
					</li>
					<li>
						<div class="names">
							<span>Влaделец: </span>
						</div>
						<div class="contents">
							<strong>
								<?
									$create = preg_replace('/\(.*\)/i','', $arResult["FIELDS"]["CREATED_USER_NAME"]);
								?>
								<?=$create?>
							</strong>
							<a href="mailto:<?=$arResult["PROPERTIES"]["PROP_EMAIL"]["VALUE"]?>">
								<i class="fa fa-envelope-o"></i>
								Написать сообщение
							</a>
						</div>
					</li>
					<li>
						<div class="names">
							<span>Телефон: </span>
						</div>
						<div class="contents">
							<span>
								<?$phone = preg_replace('/[^0-9]/i', '', 
																					$arResult["PROPERTIES"]["PROP_PHONE"]["VALUE"]);?>
								<?=$phone?>
							</span>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>
					

<div class="container">		
	<div class="grid-12">
		<div class="block">
			<div class="a-inside">
				<div class="a-information">
					<div class="a-description">
						<p><?=$arResult["DETAIL_TEXT"];?></p>
					</div>
					<ul class="a-stats">
						<li>Номер объявления <?=$arResult["ID"]?></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
<div class="clear"></div>

		
		
				<?if(array_key_exists("USE_SHARE", $arParams) && $arParams["USE_SHARE"] == "Y")
				{
					?>
					<div class="news-detail-share">
						<noindex>
						<?
						$APPLICATION->IncludeComponent("bitrix:main.share", "", array(
								"HANDLERS" => $arParams["SHARE_HANDLERS"],
								"PAGE_URL" => $arResult["~DETAIL_PAGE_URL"],
								"PAGE_TITLE" => $arResult["~NAME"],
								"SHORTEN_URL_LOGIN" => $arParams["SHARE_SHORTEN_URL_LOGIN"],
								"SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
								"HIDE" => $arParams["SHARE_HIDE"],
							),
							$component,
							array("HIDE_ICONS" => "Y")
						);
						?>
						</noindex>
					</div>
					<?
				}
				?>
</div>		