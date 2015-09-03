<?php
function tanvas_get_button($link, $text){
    return '[button link="'.$link.'" class="tanvas"]'.__($text, TANVAS_DOMAIN).'[/button]';
}

function tanvas_get_help_button(){
    return tanvas_get_button( '/my-account/help', 'Help');
    // return '[button link="/my-account/help" bg_color="#d1aa67"]'.__('Help', TANVAS_DOMAIN).'[/button]';
}

function tanvas_get_login_button(){
    return tanvas_get_button( wp_login_url(), 'Log In');
    // return tanvas_get_button('/my-account', 'Log In');
    // return '[button link="/my-account/" bg_color="#d1aa67"]'.__('Log In', TANVAS_DOMAIN).'[/button]';
}

function tanvas_get_register_button(){
    return tanvas_get_button( wp_registration_url(), 'Register');
    // return tanvas_get_button('/create-account', 'Register');
    // return '[button link="/create-account/" bg_color="#d1aa67"]'.__('Register', TANVAS_DOMAIN).'[/button]';
}

function tanvas_get_continue_shopping_button(){
    return tanvas_get_button('/shop', 'Continue Shopping');
    // return '[button link="/shop/" bg_color="#d1aa67"]'.__('Continue Shopping', TANVAS_DOMAIN).'[/button]';
}

function tanvas_get_upgrade_account_button($name = null){
    return tanvas_get_button('/my-account/upgrade'.$name?'?name='.esc_attr($name):'', 'Upgrade Account');
}

function tanvas_display_user_cap_warnings($read_caps, $object_type){
    $user_id = get_current_user_id();
    if($read_caps){ //cat is restricted
        $group_str = '"'. implode(', ', $read_caps). '"';
        $first_group = $groups[0];

        if($user_id){//logged in
            $instructions = __('apply for a wholesale account or continue shopping for other products.', TANVAS_DOMAIN).' </br>'. implode(' ', array(
                tanvas_get_continue_shopping_button(),
                tanvas_get_upgrade_account_button(),
                tanvas_get_help_button()
            ));
        } else {//not logged in
            $instructions = __('log in or create an account.', TANVAS_DOMAIN).' </br>'. implode(' ', array(
                tanvas_get_login_button(),
                tanvas_get_register_button(),
                tanvas_get_help_button()
            ));
        }
        
        echo do_shortcode(
            '[groups_non_member group='.$group_str.']'.
                '[box type="alert"]'.
                    __('This '.$object_type.' is not visible to you because you do not have the correct privileges. ', TANVAS_DOMAIN).
                    __('To view these products please ', TANVAS_DOMAIN).
                    $instructions .
                '[/box]'.
            '[/groups_non_member]'
        );                      

    } 
}

function tanvas_display_user_membership_warnings($required_membership_plans, $object_type) {
    if($required_membership_plans){
        $_procedure = 'MEMBERSHIP_DISPLAY_WARN: ';
        error_log($_procedure."commencing");

        $user_id = get_current_user_id();

        $visible = false;
        if(!$user_id){
            $instructions = __('log in or create an account.', TANVAS_DOMAIN).' </br>'. implode(' ', array(
                tanvas_get_login_button(),
                tanvas_get_register_button(),
                tanvas_get_help_button()
            ));
        } else {
            $possible_membership_plans = wc_memberships_get_membership_plans();
            foreach ($possible_membership_plans as $plan) {
                if(wc_memberships_is_user_active_member($user_id, $plan)){
                    $visible = true;
                    break;
                }
            }
            $first_plan = $required_membership_plans[0];
            $first_plan_name = $first_plan->get_name();
            error_log($_procedure."first plan name:".serialize($first_plan_name));
            $instructions = __('apply for a '.$first_plan_name.' account or continue shopping for other products.', TANVAS_DOMAIN).' </br>'. implode(' ', array(
                tanvas_get_continue_shopping_button(),
                tanvas_get_upgrade_account_button($first_plan_name),
                tanvas_get_help_button()
            ));
        }

        if(!$visible){
            error_log($_procedure."category is not visible");

            echo do_shortcode(
                '[box type="alert"]'.
                    __('This '.$object_type.' is not visible to you because you do not have the correct privileges. ', TANVAS_DOMAIN).
                    __('To view these products please ', TANVAS_DOMAIN).
                    $instructions .
                '[/box]'
            );  
        } else {
            error_log($_procedure."category is visible");
        }
        
        error_log($_procedure."complete");
    }
}

function tanvas_display_unrestricted_login_warning(){
    $user_id = get_current_user_id();
    if(!$user_id){
        echo do_shortcode(
            '[box type="info"]'.
                __('You may not be getting the best deal! Log in or create an account to get prices crafted specially for you.', TANVAS_DOMAIN).'<br/>'.
                tanvas_get_login_button() . ' ' . tanvas_get_help_button() .
            '[/box]'
        );
    }
}

function tanvas_woocommerce_category_warning() {
    $_procedure = 'MEMBERSHIP_WARNINGS: ';
    if ( is_product_category() ){
        global $wp_query;
        $cat = $wp_query->get_queried_object();
        $term_id = $cat->term_id;

        if(TANSYNC_DEBUG) error_log($_procedure."current category: ".$term_id);

        $read_caps = null;
        if(class_exists('Groups_Restrict_Categories')){
            $read_caps = Groups_Restrict_Categories::get_term_read_capabilities( $term_id );
        }
        tanvas_display_user_cap_warnings($read_caps, 'category');

        //Membership Integration
        $required_memberships = null;
        error_log($_procedure."commencing");
        if(class_exists('WC_Memberships')){
            
            // get required membership plan
            $possible_membership_plans = wc_memberships_get_membership_plans();
            foreach ($possible_membership_plans as $plan) {
                error_log($_procedure."processing plan | ".$plan->get_name());
                $product_restriction_rules = $plan->get_product_restriction_rules();
                $rules_contain_term = false;
                foreach ($product_restriction_rules as $rule) {
                    if($rule->get_content_type() == 'taxonomy'){
                        error_log($_procedure." -> Taxonomy Rule: ".$rule->get_id());
                        $this_id = $term_id;
                        do {
                            $term = get_term_by('id', $this_id, 'product_cat');
                            error_log($_procedure." --> term: ".$this_id);
                            if($rule->applies_to_single_object($this_id)){
                                error_log($_procedure." ---> applies to this category");
                                $rules_contain_term = true;
                                break;
                            } else {
                                error_log($_procedure." ---> does not apply to this category: ");
                            }
                            $this_id = $term->parent;
                        } while($this_id);
                    }
                }
                if ($rules_contain_term) {
                    $required_memberships[] = $plan;
                }
            }
            
        }
        error_log($_procedure."required memberships:");
        if($required_memberships) foreach ($required_memberships as $membership) {
            error_log($_procedure." -> ".serialize($membership));
        }
        error_log($_procedure."displaying warnings:");
        tanvas_display_user_membership_warnings($required_memberships, 'category');

        if(!$required_memberships and !$read_caps){
            error_log($_procedure."no required_memberships or read_caps");
            tanvas_display_unrestricted_login_warning();
        }

        error_log($_procedure."complete");
    }
}

function tanvas_woocommerce_product_warning(){
    if( is_product() ){
        global $product;
        $product_id = $product->id;
        $read_caps = null;
        if(class_exists('Groups_Post_Access')){
            $read_caps = Groups_Post_Access::get_read_post_capabilities( $product_id );
        }
        tanvas_display_user_cap_warnings($read_caps, 'product');

        //Membership Integration
        if(class_exists('WC_Memberships')){
            // get required membership plan
            $required_memberships = array();
            //TODO THIS
            tanvas_display_user_membership_warnings($required_memberships, 'product');
        }
    }
}

add_action( 'woocommerce_archive_description', 'tanvas_woocommerce_category_warning', 15 );

add_action( 'woocommerce_single_product_summary', 'tanvas_woocommerce_product_warning', 7);
?>