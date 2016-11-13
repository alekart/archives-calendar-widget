<?php
function preview_block()
{
	?>

	<div id="ac_preview" style="display:none;">

		<div class="preview_theme_select">
			<li class="selector-title"><?php _e('Themes'); ?></li>
			<?php
			global $themes;
			foreach ($themes as $theme => $title) {
				echo '<li id="' . $theme . '" class="preview-theme">' . $title . '</li>';
			}
			if ($custom = get_option('archivesCalendarThemer')) {
				$i = 1;
				foreach ($custom as $filename => $css) {
					if ($css) {
						echo '<li id="' . $filename . '" class="preview-theme">' . __('Custom') . ' ' . $i . '</li>';
						$i++;
					}
				}
			}
			?>
		</div>
		<div class="arcw-preview-container">
			<div class="arcw preview-zone">
				<?php
				year_preview_html();
				month_preview_html();
				?>
			</div>
			<p class="preview-note">
				<span
					class="description"><?php _e("The theme's CSS file is not included in administration, this preview may be different from the website rendering.", 'arwloc'); ?></span>
			</p>

			<div class="modal-buttons">
				<button class="button-primary ok_theme"><?php _e('Select'); ?></button>
				<button class="button cancel_theme"><?php _e('Close'); ?></button>
			</div>
		</div>
	</div>

	<?php
}

function year_preview_html()
{
	?>
	<div class="calendar-archives arw-theme1 arw-theme2">
		<div class="calendar-navigation">
			<a href="#" class="prev-year"><span>&lt;</span></a>
			<div class="menu-container years">
				<a href="#" class="title"> 2014</a>
				<ul class="menu" style="top: 0px;">
					<li><a href="#" class="2015 current selected" rel="0">2015</a></li>
					<li><a href="#" class="2014" rel="0">2014</a></li>
					<li><a href="#" class="2013" rel="1">2013</a></li>
				</ul>
				<div class="arrow-down" title="Selectioner une année">
					<span>▼</span>
				</div>
			</div>
			<a href="#" class="next-year disabled"><span>&gt;</span></a>
		</div>
		<div class="archives-years">
			<div class="year 2014 last current" rel="0">
				<div class="month">
					<span class="month-name">jan</span>
				            <span class="postcount"><span class="count-number">0</span><span class="count-text">Posts</span>
				            </span>
				</div>
				<div class="month">
					<span class="month-name">feb</span>
					<span class="postcount"><span class="count-number">0</span> <span class="count-text">Posts</span></span>
				</div>
				<div class="month">
					<span class="month-name">mar</span>
					<span class="postcount"><span class="count-number">0</span> <span class="count-text">Posts</span></span>
				</div>
				<div class="month has-posts last">
					<a href="#">
						<span class="month-name">apr</span>
						<span class="postcount"><span class="count-number">4</span> <span class="count-text">Posts</span></span>
					</a>
				</div>
				<div class="month has-posts">
					<a href="#">
						<span class="month-name">may</span>
						<span class="postcount"><span class="count-number">1</span> <span class="count-text">Post</span></span>
					</a>
				</div>
				<div class="month">
					<span class="month-name">jun</span>
					<span class="postcount"><span class="count-number">0</span> <span class="count-text">Posts</span></span>
				</div>
				<div class="month">
					<span class="month-name">jul</span>
					<span class="postcount"><span class="count-number">0</span> <span class="count-text">Posts</span></span>
				</div>
				<div class="month last">
					<span class="month-name">aug</span>
					<span class="postcount"><span class="count-number">0</span> <span class="count-text">Posts</span></span>
				</div>
				<div class="month has-posts">
					<a href="#">
						<span class="month-name">sep</span>
						<span class="postcount"><span class="count-number">1</span> <span class="count-text">Post</span></span>
					</a>
				</div>
				<div class="month has-posts">
					<a href="#">
						<span class="month-name">oct</span>
						<span class="postcount"><span class="count-number">3</span> <span class="count-text">Posts</span></span>
					</a>
				</div>
				<div class="month">
					<span class="month-name">nov</span>
					<span class="postcount"><span class="count-number">0</span> <span class="count-text">Posts</span></span>
				</div>
				<div class="month last">
					<span class="month-name">dec</span>
					<span class="postcount"><span class="count-number">0</span> <span class="count-text">Posts</span></span>
				</div>
			</div>
		</div>
	</div>
	<?php
}

function month_preview_html(){
?>
	<div class="calendar-archives arw-theme1 arw-theme2">
		<div class="calendar-navigation">
			<a href="#" class="prev-year"><span>&lt;</span></a>
			<div class="menu-container months">
				<a href="#" class="title">january 2015</a>
				<ul class="menu" style="top: 0px; display: none;">
					<li><a href="#" class="2015 01 current selected" rel="0">january 2015</a></li>
					<li><a href="#" class="2014 12" rel="0">september 2014</a></li>
					<li><a href="#" class="2011 10" rel="0">october 2011</a></li>
					<li><a href="#" class="2011 06" rel="0">june 2011</a></li>
				</ul>
				<div class="arrow-down" title="Select archives year"><span>▼</span></div>
			</div><a href="#" class="next-year disabled"><span>&gt;</span></a>
		</div>
		<div class="week-row weekdays">
			<span class="day weekday">mon</span>
			<span class="day weekday">thu</span>
			<span class="day weekday">wen</span>
			<span class="day weekday">tue</span>
			<span class="day weekday">fri</span>
			<span class="day weekday">sat</span>
			<span class="day weekday last">sun</span>
		</div>
		<div class="archives-years">
			<div class="year 12 2011 current" rel="0">
				<div class="week-row">
					<span class="day noday">&nbsp;</span>
					<span class="day noday">&nbsp;</span>
					<span class="day noday">&nbsp;</span>
					<span class="day">1</span>
					<span class="day">2</span>
					<span class="day">3</span>
					<span class="day last">4</span>
				</div>
				<div class="week-row">
					<span class="day">5</span>
					<span class="day">6</span>
					<span class="day has-posts"><a href="#">7</a></span>
					<span class="day">8</span>
					<span class="day">9</span>
					<span class="day">10</span>
					<span class="day has-posts last"><a href="#">11</a></span>
				</div>
				<div class="week-row">
					<span class="day">12</span>
					<span class="day">13</span>
					<span class="day has-posts"><a href="#">14</a></span>
					<span class="day has-posts"><a href="#">15</a></span>
					<span class="day">16</span>
					<span class="day">17</span>
					<span class="day has-posts last"><a href="#">18</a></span>
				</div>
				<div class="week-row">
					<span class="day has-posts"><a href="#">19</a></span>
					<span class="day has-posts"><a href="#">20</a></span>
					<span class="day has-posts"><a href="#">21</a></span>
					<span class="day has-posts"><a href="#">22</a></span>
					<span class="day">23</span><span class="day">24</span>
					<span class="day last">25</span>
				</div>
				<div class="week-row"><span class="day">26</span><span class="day">27</span><span class="day">28</span><span class="day">29</span><span class="day">30</span><span class="day">31</span><span class="day noday last">&nbsp;</span>
				</div>
			</div>
			<div class="year 12 2010 last" rel="0">
				<div class="week-row"><span class="day last">&nbsp;</span>
				</div>
				<div class="week-row"><span class="day last">&nbsp;</span>
				</div>
				<div class="week-row"><span class="day last">&nbsp;</span>
				</div>
				<div class="week-row"><span class="day last">&nbsp;</span>
				</div>
				<div class="week-row"><span class="day last">&nbsp;</span>
				</div>
			</div>
		</div>
	</div>
<?php
}