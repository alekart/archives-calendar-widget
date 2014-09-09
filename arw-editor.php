<?php
/*
Archives Calendar Widget THEME EDITOR
Author URI: http://alek.be
License: GPLv3
*/

?>
<div class="wp-full-overlay-sidebar-content accordion-container postbox-container" id="postbox-container-1" style="border-right: 1px solid #ddd; border-left: 1px solid #ddd; float: left " tabindex="-1">
	<div id="customize-info" class="accordion-section ">
		<div class="accordion-section-title" aria-label="Options de personnalisation du thème" tabindex="0">
			<span class="preview-notice"><strong class="theme-name"><?php _e('Theme');?>: <?php _e('Custom', 'arwloc');?></strong></span>
		</div>
		<div class="accordion-section-content">
			<div class="theme-description">
				Ce théme peut être personnalisé à votre guise et sera sauvegardé dans les réglages du plugin. Contrairement aux thémes inclus, ce théme ne sera pas effacé/restoré à la mise à jour.
				Une fois configuré vous devez "Générer le théme" et l'appliquer dans les réglages du plugin en séléctionnant le théme "Custom".
			</div>
		</div>
	</div>

	<div id="customize-theme-controls">
		<ul>
			<li id="accordion-section-colors" class="control-section accordion-section">
				<h3 class="accordion-section-title" tabindex="0">Couleurs de la barre de navigation</h3>
				<ul class="accordion-section-content">

					<li id="customize-control-nav_bg" class="customize-control customize-control-color">
						<label>
							<span class="customize-control-title">Couleur de fond</span>
							<div class="customize-control-content">
								<input id="cal-nav__background" type='text' class='color-field cal'>
							</div>
						</label>
					</li>

					<li id="customize-control-nav_textcolor" class="customize-control customize-control-color">
						<label>
							<span class="customize-control-title">Couleur du texte</span>
							<div class="customize-control-content">
								<input id="cal-nav a__color" type='text' class='color-field cal'>
							</div>
						</label>
					</li>
				</ul>
			</li>

			<li id="accordion-section-colors" class="control-section accordion-section">
				<h3 class="accordion-section-title" tabindex="0">Couleurs des fleches</h3>
				<ul class="accordion-section-content">

					<li id="customize-control-header_textcolor" class="customize-control customize-control-color">
						<label>
							<span class="customize-control-title">Active</span>
							<div class="customize-control-content">
								<input id="cal-nav a.prev-year__color" type='text' class='color-field cal'>
							</div>
						</label>
					</li>

					<li id="customize-control-header_textcolor" class="customize-control customize-control-color">
						<label>
							<span class="customize-control-title">Survolé</span>
							<div class="customize-control-content">
								<input type='text' class='color-field'>
							</div>
						</label>
					</li>

                	<li id="customize-control-background_color" class="customize-control customize-control-color">
						<label>
							<span class="customize-control-title">Désactivé</span>
							<div class="customize-control-content">
								<input type='text' class='color-field'>
							</div>
						</label>
					</li>

				</ul>
			</li>

			<li id="accordion-section-colors" class="control-section accordion-section">
				<h3 class="accordion-section-title" tabindex="0">Couleurs</h3>
				<ul class="accordion-section-content">

					<li id="customize-control-header_textcolor" class="customize-control customize-control-color">
						<label>
							<span class="customize-control-title">Couleur de la navigation</span>
							<div class="customize-control-content">
								<input type='text' class='color-field'>
							</div>
						</label>
					</li>

					<li id="customize-control-header_textcolor" class="customize-control customize-control-color">
						<label>
							<span class="customize-control-title">Couleur du mois/jour</span>
							<div class="customize-control-content">
								<input type='text' class='color-field'>
							</div>
						</label>
					</li>

                	<li id="customize-control-background_color" class="customize-control customize-control-color">
						<label>
							<span class="customize-control-preview-bg">Couleur du mois/jour vide</span>
							<div class="customize-control-content">
								<input type='text' class='color-field'>
							</div>
						</label>
					</li>

				</ul>
			</li>
		</ul>
	</div>
</div>

<div id="post-body-content" class="prewiever">
	<div class="themer-container">
		<div class="themer-bg">
			<label>
				<span class="customize-control-preview-bg"></span>
				<div class="customize-control-content">
					<input id="themer-container__background" type='text' data-default-color="#ffffff" class='color-field'>
				</div>
			</label>
		</div>
		<div class="arcw themer zone" style="width:260px; margin:auto; padding:50px 0;">
			
			<div class="calendar-archives">
				<div class="cal-nav">
					<a href="#" class="prev-year"><span>&lt;</span></a>
					<div class="year-nav">
						<a href="#" class="year-title">2013</a>
						<div class="year-select" style="top: 0px;">
							<a href="#" class="year 2013 current selected" rel="0">2013</a>
							<a href="#" class="year 2012" rel="1">2012</a>
						</div>
						<div class="arrow-down" title="<?php _e( 'Select archives year', 'arwloc') ;?>">
							<span>▼</span>
						</div>
					</div>
					<a href="#" class="next-year disabled"><span>&gt;</span></a>
				</div>
				<?php 
				$aloc = 'archives_calendar';
				$mnames[0] = '';

				for($i=1; $i<13; $i++)
				{
					$monthdate = '1970-'. sprintf('%02d', $i) .'-01';		
					$mnames[$i] = $wp_locale->get_month_abbrev( $wp_locale->get_month(intval($i)) );
				}
					
				$years = array(2013 => array( 3 => 4, 6 => 3, 1 => 2 ), 2012 => array( 2 => 4, 7 => 3, 8 => 2 ));

				$cal= '<div class="archives-years" style="position:relative">';
				$i = 0;
				
				
				foreach ($years as $year => $months){
					$current = ($i == 0) ? " current" : "";
					$lastyear = ($i == 1) ? " last" : "";
					$cal .= '<div class="year '.$year.$current.$lastyear.'" rel="'.$i.'">';
					for ( $month = 1; $month <= 12; $month++ ) {
						$last = ( $month%4 == 0 ) ? ' last' : '';
							if(isset($months[$month])) $count = $months[$month];
							else $count = '0';
							$posts_text = ($count == 1) ? __('Post', 'arwloc') : __('Posts', 'arwloc');

							$postcount = '<span class="postcount"><span class="count-number">'.$count.'</span> <span class="count-text">'.$posts_text.'</span></span>';
						
						if(isset($months[$month]))
							$cal .= '<div class="month'.$last.'"><a href="#"><span class="month-name">'.$mnames[$month].'</span>'.$postcount.'</a></div>';
						else
							$cal .= '<div class="month'.$last.' empty"><span class="month-name">'.$mnames[$month].'</span>'.$postcount.'</div>';
					}
					$cal .= "</div>\n";
					$i++;
				}

				$cal .= "</div>";
				echo $cal;
				?>
			</div>
		</div>
	</div>
	<div>
	generated css
	<textarea rows="5" style="width:100%;"></textarea>
	</div>
	<input name="Submit" type="button" style="margin:20px 0;" class="button-primary" value="<?php _e('Save'); ?>" /> 
	<input name="Submit" type="button" style="margin:20px 0;" class="button" value="<?php _e('Reset', 'arwloc'); ?>" />
</div>

