<div class="archives-calendar-widget-wettings">
    <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
    </p>
    <p>
        <div style="float: left; width: 49%">
            <label for="<?php echo $this->get_field_id( 'prev_text' ); ?>"><?php _e( 'Previous' ); ?>:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'prev_text' ); ?>" name="<?php echo $this->get_field_name( 'prev_text' ); ?>" type="text" value="<?php echo esc_attr( $prev ); ?>" />
        </div>
        <div style="float: right; width: 49%">
            <label for="<?php echo $this->get_field_id( 'next_text' ); ?>"><?php _e( 'Next' ); ?>:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'next_text' ); ?>" name="<?php echo $this->get_field_name( 'next_text' ); ?>" type="text" value="<?php echo esc_attr( $next ); ?>" />
        </div>
        <div class="clear"></div>
    </p>

    <hr/>
    <p>
        <label for="<?php echo $this->get_field_id( 'month_view' ); ?>"><?php _e( 'Show' ).': '; ?></label>
        <select id="arw-view" name="<?php echo $this->get_field_name( 'month_view' ); ?>" >
            <option <?php selected( 1, $month_view ); ?> value="1">
                <?php _e( 'Months' ); ?>
            </option>
            <option <?php selected( 0, $month_view ); ?> value="0">
                <?php _e( 'Years' ); ?>
            </option>
        </select>
        <span>&nbsp;</span>
        <span style="display:none">
            <label for="<?php echo $this->get_field_id( 'month_view_option' ); ?>"><?php _e( 'Show first' ).': '; ?></label>
            <select id="arw-month_view-option" name="<?php echo $this->get_field_name( 'month_view_option' ); ?>" >
                <option <?php selected( 'last', $month_select ); ?> value="actual">
                    <?php _e( 'Last month' ); ?>
                </option>
                <option <?php selected( 'actual', $month_select ); ?> value="actual">
                    <?php _e( 'Actual month' ); ?>
                </option>
                <option <?php selected( 'prev', $month_select ); ?> value="prev">
                    <?php _e( 'Previous month' ); ?>
                </option>
                <option <?php selected( 'next', $month_select ); ?> value="next">
                    <?php _e( 'Next month' ); ?>
                </option>
            </select>
        </span>
        <span style="display: inline-block;">
            <label>
                <input id="arw-year_view-option" class="selectit" <?php if($count) echo "checked";?> id="<?php echo $this->get_field_id( 'post_count' ); ?>" name="<?php echo $this->get_field_name( 'post_count' ); ?>" type="checkbox" value="1" />
                &nbsp;<?php _e( 'Show number of posts', 'arwloc' ); ?>
            </label>
        </span>
    </p>

    <hr/>

    <p>
        <label>
            <input id="arw-theme-option" class="selectit" <?php if($different_theme) echo "checked";?> id="<?php echo $this->get_field_id( 'different_theme' ); ?>" name="<?php echo $this->get_field_name( 'different_theme' ); ?>" type="checkbox" value="1" />
            &nbsp;<?php _e( 'Set a different theme', 'arwloc' ); ?>
        </label>
        <div>NOT YET IMPLEMENTED</div>
    </p>

    <hr/>

    <p>
        <div class="accordion-container" style="border: 1px solid #ddd; overflow: hidden" tabindex="-1">
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
                <div class="accordion-section-content" id="<?php echo $this->get_field_id( 'post_type' );; ?>">
                    <p>
                        <label>post_type:</label>
                        <input class="widefat" id="<?php echo $this->get_field_id( 'post_type' ); ?>" name="<?php echo $this->get_field_name( 'post_type' ); ?>" type="text" value="<?php echo esc_attr( $post_type ); ?>" />
                        <p class="description" style="font-size: 12px;">Enter post_type separated by a comma ",". If not empty, only the specified types will be shown, don't forget to enter "post" if needed.<br>Example: post, event, film</p>
                    <p>
                </div>
            </div>
        </div>
    </p>

</div>