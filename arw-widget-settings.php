
<div class="archives-calendar <?php echo $this->id; ?>">
    <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
    </p>
    <div>
        <div style="float: left; width: 49%">
            <label for="<?php echo $this->get_field_id( 'prev_text' ); ?>"><?php _e( 'Previous', 'arwloc' ); ?>:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'prev_text' ); ?>" name="<?php echo $this->get_field_name( 'prev_text' ); ?>" type="text" value="<?php echo $prev; ?>" />
        </div>
        <div style="float: right; width: 49%">
            <label for="<?php echo $this->get_field_id( 'next_text' ); ?>"><?php _e( 'Next', 'arwloc' ); ?>:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'next_text' ); ?>" name="<?php echo $this->get_field_name( 'next_text' ); ?>" type="text" value="<?php echo $next; ?>" />
        </div>
        <div class="clear"></div>
    </div>
    <br/>
    <hr/>
    <p>
        <label for="<?php echo $this->get_field_id( 'month_view' ); ?>">
            <?php _e( 'Show' ); ?>:
        </label>
            <select id="arw-view" name="<?php echo $this->get_field_name( 'month_view' ); ?>" >
                <option <?php selected( 1, $month_view ); ?> value="1">
                    <?php _e( 'Months', 'arwloc' ); ?>
                </option>
                <option <?php selected( 0, $month_view ); ?> value="0">
                    <?php _e( 'Years', 'arwloc' ); ?>
                </option>
            </select>
        <span>&nbsp;</span>
		<span class="monthOpt" style="display: <?php if($month_view) echo "inline-block"; else echo "none"; ?>">
            <label for="<?php echo $this->get_field_id( 'month_select' ); ?>">
                <?php _e( 'Show first: ', 'arwloc' ); ?>
            </label>
            <select id="arw-month_view-option" name="<?php echo $this->get_field_name( 'month_select' ); ?>">
                <option <?php selected( 'default', $month_select ); ?> value="default">
                    <?php _e( 'Latest month', 'arwloc' ); ?>
                </option>
                <option <?php selected( 'current', $month_select ); ?> value="current">
                    <?php _e( 'Current month', 'arwloc' ); ?>
                </option>
                <option <?php selected( 'prev', $month_select ); ?> value="prev">
                    <?php _e( 'Previous month', 'arwloc' ); ?>
                </option>
                <option <?php selected( 'next', $month_select ); ?> value="next">
                    <?php _e( 'Next month', 'arwloc' ); ?>
                </option>
            </select>
        </span>
        <span class="yearOpt" style="display: <?php if(!$month_view) echo "inline-block"; else echo "none"; ?>;">
            <label>
                <input id="arw-year_view-option" class="selectit" <?php if($count) echo "checked";?> id="<?php echo $this->get_field_id( 'post_count' ); ?>" name="<?php echo $this->get_field_name( 'post_count' ); ?>" type="checkbox" value="1" />
                &nbsp;<?php _e( 'Show number of posts', 'arwloc' ); ?>
            </label>
        </span>
    </p>

    <hr/>

    <p>
        <label>
            <input id="arw-disable_title_link-option" class="selectit" <?php if($disable_title_link) echo "checked";?> id="<?php echo $this->get_field_id( 'disable_title_link' ); ?>" name="<?php echo $this->get_field_name( 'disable_title_link' ); ?>" type="checkbox" value="1" />
            &nbsp;<?php _e( 'Disable title link', 'arwloc' ); ?>
        </label>
    </p>

    <hr/>

    <p>
        <label>
            <input id="arw-theme-option" class="selectit" <?php if($different_theme) echo "checked";?> id="<?php echo $this->get_field_id( 'different_theme' ); ?>" name="<?php echo $this->get_field_name( 'different_theme' ); ?>" type="checkbox" value="1" />
            &nbsp;<?php _e( 'Use a different theme', 'arwloc' ); ?>
        </label>
    </p>
    <p class="arw-theme-list" style="<?php if(!$different_theme) echo "display: none";?>">
        <?php
        arcw_themes_list($arw_theme, array( 'name' => $this->get_field_name( 'theme' ), 'id' => $this->get_field_id( 'theme' ) ) );
        ?>
    </p>

    <hr/>

	<p></p>
        <div class="accordion-container arcw-accordion" style="border: 1px solid #ddd; overflow: hidden" tabindex="-1">
            <?php
                $elemid = $this->get_field_id( 'cats' );
            ?>
            <script>
            jQuery(function($){

                $('#<?php echo $elemid; ?> input[type=checkbox].all').on('change', function(){
                    $(this).attr('checked', true);
                    $("#<?php echo $elemid; ?> input[type=checkbox]:not(.all)").attr('checked', false);
                });

                $('#<?php echo $elemid; ?> input[type=checkbox]:not(.all)').on('change', function(){
                    $("#<?php echo $elemid; ?> input[type=checkbox].all").attr('checked', false);
                });
            })
            </script>
            <div class="control-section accordion-section acw-cats">
                <div class="accordion-section-title" tabindex="0">
                    <strong><?php _e('Categories');?></strong>
                </div>
                <div class="accordion-section-content" id="<?php echo $elemid; ?>" style="background-color: #FDFDFD">
                    <label class="selectit"><input class="all" type="checkbox" id="acw_all_cats" <?php if(!count($cats)) echo 'checked';?> > <?php _e('All'); ?></label>
                    <hr>
                    <ul id="categorychecklist" class="categorychecklist form-no-clear">
                    <?php
                        $acw_walker = new acw_Walker_Category_Checklist( array('field_id' => $this->get_field_id( 'categories' ), 'field_name' => $this->get_field_name( 'categories' ) ) );
                        $args = array(
                            'descendants_and_self'  => 0,
                            'selected_cats'         => $cats,
                            'popular_cats'          => false,
                            'walker'                => $acw_walker,
                            'taxonomy'              => 'category',
                            'checked_ontop'         => true,
                        );
                        wp_category_checklist( 0, 0, $cats, false, $acw_walker, false);
                    ?>
                    </ul>
                </div>
            </div>
            <div class="control-section accordion-section acw-post_type">
                <div class="accordion-section-title" tabindex="0">
                    <strong>Post type</strong>
                </div>
                <div class="accordion-section-content" id="<?php echo $this->get_field_id( 'post_type' ); ?>">
                    <p>
                        <ul id="ptypechecklist" class="categorychecklist form-no-clear">
                            <?php

                            $checkbox_tmpl = '<li class="popular-category">
                                                <label class="selectit">
                                                    <input value="%s" type="checkbox" id="%s" name="%s" %s> 
                                                    %s
                                                </label>
                                            </li>';

                            $args = array(
                                'public'   => true,
                                '_builtin' => false
                            );
                            $post_types = get_post_types($args);

                            $post_checked = $post_type && is_array($post_type) ? checked( in_array( 'post', $post_type ), true, false ) : 'checked="checked"';

                            echo sprintf($checkbox_tmpl, 'post', $this->get_field_id( 'post_type' ), $this->get_field_name( 'post_type' ).'[]', $post_checked, 'post');

                            foreach($post_types as $type)
                            {
                                echo sprintf($checkbox_tmpl, $type, $this->get_field_id( 'post_type' ), $this->get_field_name( 'post_type' ).'[]', checked( in_array( $type, $post_type ), true, false ), $type);
                            }
                            ?>
                        </ul>
                    <p>
                </div>
            </div>
        </div>
	<p></p>
</div>