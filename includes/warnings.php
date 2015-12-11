<?php

/**
 *  Buttons
 */

function tanvas_get_button($link, $text) {
    return '[button link="' . $link . '" class="tanvas"]' . __($text, TANVAS_DOMAIN) . '[/button]';
}

function tanvas_get_help_button() {
    return tanvas_get_button('/my-account/help', 'Help');
    
    // return '[button link="/my-account/help" bg_color="#d1aa67"]'.__('Help', TANVAS_DOMAIN).'[/button]';
    
}

function tanvas_get_login_button() {
    
    // if( is_user_logged_in() ){
    //     return "";
    // } else {
    return tanvas_get_button(wp_login_url(), 'Log In');
    
    // }
    
    // return tanvas_get_button('/my-account', 'Log In');
    // return '[button link="/my-account/" bg_color="#d1aa67"]'.__('Log In', TANVAS_DOMAIN).'[/button]';
    
}

function tanvas_get_register_button() {
    return tanvas_get_button(wp_registration_url(), 'Register');
    
    // return tanvas_get_button('/create-account', 'Register');
    // return '[button link="/create-account/" bg_color="#d1aa67"]'.__('Register', TANVAS_DOMAIN).'[/button]';
    
}

function tanvas_get_continue_shopping_button() {
    return tanvas_get_button('/shop', 'Continue Shopping');
    
    // return '[button link="/shop/" bg_color="#d1aa67"]'.__('Continue Shopping', TANVAS_DOMAIN).'[/button]';
    
}

function tanvas_get_upgrade_account_button($name = null) {
    return tanvas_get_button('/my-account/upgrade' . $name ? '?name=' . esc_attr($name) : '', 'Upgrade Account');
}

function tanvas_get_warning_buttons($authorized = null) {
    $help_button = tanvas_get_help_button();
    $buttons = array($help_button);
    if (!$authorized) {
        if (is_user_logged_in()) {
            $upgrade_button = tanvas_get_upgrade_account_button();
            array_push($buttons, $upgrade_button);
        } 
        else {
            $login_button = tanvas_get_login_button();
            array_push($buttons, $login_button);
            
            // $register_button = tanvas_get_register_button();
            // array_push($buttons, $register_button);
            
        }
    }
    return $buttons;
}

/**
 *  Warning Display Functions
 */

function tanvas_get_warning_string($warning_type, $message, $instructions, $buttons) {
    if (!$warning_type) {
        $warning_type = 'alert';
    }
    if (!$message) {
        $message = __('This item is restricted', TANVAS_DOMAIN);
        if (!is_user_logged_in()) {
            $message+= ' because you are not logged in.';
        }
    }
    if (!$instructions and !is_user_logged_in()) {
        $instructions = __('Please log in or create an account.', TANVAS_DOMAIN);
    }
    $lines = array($message);
    if ($instructions) {
        $lines[] = $instructions;
    }
    if ($buttons and is_array($buttons)) {
        $lines[] = implode(' ', $buttons);
    }
    return '[box type="' . $warning_type . '"]' . implode('<br/>', $lines) . '[/box]';
}

function tanvas_display_tier_warnings($required_tiers, $user_tiers, $object_type) {
    $_procedure = "DISPLAY_TIER_WARN: ";
    if ($required_tiers) {
        $buttons = tanvas_get_warning_buttons();
        $instructions = "";
        $box_type = "";
        $authority = tanvas_get_tier_authority($user_tiers);
        $message = __('You are viewing our site at ', TANVAS_DOMAIN) . ' ' . $authority . '.';
        
        if (is_user_logged_in()) {
            $required_authority = tanvas_get_tier_authority($required_tiers);
            // $message.= __('</br>This item is restricted to ', TANVAS_DOMAIN) . ' ' . $required_authority . '.';
            $instructions = __("Please apply for a $required_authority account to continue.", TANVAS_DOMAIN);
        } 
        else {
            // $message.= __('</br>This item is restricted because you are not logged in.', TANVAS_DOMAIN);
            $instructions = __("Please log in at $required_authority to continue.", TANVAS_DOMAIN);
        }
        
        echo do_shortcode(tanvas_get_warning_string($box_type, $message, $instructions, $buttons));
        
        return true;
    } 
    else {
        error_log($_procedure . "no reqwuired tiers");
        return false;
    }
}

function tanvas_display_user_membership_warnings($required_membership_plans, $user_memberships, $object_type) {
    $_procedure = 'DISPLAY_MEMBERSHIP_WARN: ';
    if ($required_membership_plans) {
        $buttons = tanvas_get_warning_buttons();
        $message = "";
        $instructions = "";
        $box_type = "";
        
        if (is_user_logged_in()) {
            $first_plan = $required_membership_plans[0];
            $first_plan_name = $first_plan->get_name();
            error_log($_procedure . "first plan name:" . serialize($first_plan_name));
            $instructions = __("Please apply for a $first_plan_name plan or continue shopping.", TANVAS_DOMAIN);
            $buttons[] = tanvas_get_continue_shopping_button();
        } 
        else {
            $buttons = tanvas_get_warning_buttons();
        }
        
        echo do_shortcode(tanvas_get_warning_string($box_type, $message, $instructions, $buttons));
        
        return true;
    } 
    else {
        error_log($_procedure . "no required_memberships");
        return false;
    }
}

function tanvas_display_group_warnings($required_caps, $user_groups, $object_type) {
    $_procedure = 'DISPLAY_GROUP_WARN: ';
    if ($required_caps) {
        $buttons = tanvas_get_warning_buttons();
        $message = "";
        $instructions = "";
        $box_type = "";
        
        if (is_user_logged_in()) {
            $group_str = '"' . implode(', ', $required_caps) . '"';
            $first_group = $required_caps[0];
            $reason = __('because you do not have the correct privileges', TANVAS_DOMAIN);
            $required_authority = tanvas_get_group_authority($required_caps);
            $instructions = __('Please apply for ' . $required_authority . ' access or continue shopping.', TANVAS_DOMAIN);
            array_push($buttons, tanvas_get_continue_shopping_button());
        }
        
        echo do_shortcode('[groups_non_member group=' . $group_str . ']' . tanvas_get_warning_string($box_type, $message, $instructions, $buttons) . '[/groups_non_member]');
        return true;
    } 
    else {
        error_log($_procedure . "no required_memberships");
        return false;
    }
}

function tanvas_display_unrestricted_warning() {
    $_procedure = "MEMBERSHIP_UNRESTRICTED: ";
    if (TANVAS_DEBUG) error_log($_procedure . "start");
    
    $buttons = tanvas_get_warning_buttons();
    $authority = tanvas_get_tier_authority();
    echo do_shortcode('[box type="info"]' . __('You are currently viewing our site at', TANVAS_DOMAIN) . ' ' . $authority . '<br/>' . implode(' ', $buttons) . '[/box]');
    if (TANVAS_DEBUG) error_log($_procedure . "end");
}

/**
 *  Authority functions
 */

function tanvas_get_authority_string($names, $default = 'Public') {
    if ($names) {
        return implode(' / ', $names);
    } 
    else {
        return $default;
    }
}

function tanvas_get_user_tiers($user = null) {
    $_procedure = 'GET_USER_TIERS: ';
    
    $tiers = array();
    if (!$user) {
        $user = wp_get_current_user();
    }
    
    $user_id = $user->ID;
    
    if (class_exists('Lasercommerce_Plugin')) {
        global $Lasercommerce_Plugin;
        if (isset($Lasercommerce_Plugin)) {
            $tiers = $Lasercommerce_Plugin->tree->getUserTiers($user_id);
        }
    } 
    else {
        if (TANVAS_DEBUG) error_log($_procedure . "LC plugin class DNE");
    }
    
    if (TANVAS_DEBUG) error_log($_procedure . "tiers: " . serialize($tiers));
    return $tiers;
}

function tanvas_get_tier_authority($tiers = array()) {
    $public_authority = 'Retail';
    $names = array();
    if ($tiers) {
        foreach ($tiers as $tier) {
            array_push($names, $tier->name);
        }
    }
    return tanvas_get_authority_string($names, $public_authority);
}

function tanvas_get_user_memberships($user = null) {
    $_procedure = 'GET_USER_MEMBERSHIPS: ';
    
    $memberships = array();
    if (!$user) {
        $user = wp_get_current_user();
    }
    
    $user_id = $user->ID;
    
    if (function_exists('wc_memberships_get_user_memberships')) {
        $memberships = wc_memberships_get_user_memberships($user_id, array('status' => 'active'));
    }
    
    if (TANVAS_DEBUG) error_log($_procedure . "memberships: " . serialize($memberships));
    return $memberships;
}

function tanvas_get_memberships_authority($memberships) {
    $names = array();
    if ($memberships) {
        foreach ($memberships as $membership) {
            array_push($names, $membership->name);
        }
    }
    return tanvas_get_authority_string($names, null);
}

function tanvas_get_user_groups($user = null) {
    $_procedure = 'GET_USER_GROUPS: ';
    
    $groups = array();
    if (!$user) {
        $user = wp_get_current_user();
    }
    $user_id = $user->ID;
    
    if (class_exists('Groups_User')) {
        $groups_user = new Groups_User($user_id);
        $groups = $groups_user->groups;
    }
    
    if (TANVAS_DEBUG) error_log($_procedure . "groups: " . serialize($groups));
    
    return $groups;
}

function tanvas_get_group_authority($groups = array()) {
    $names = array();
    if ($groups) {
        foreach ($groups as $group) {
            array_push($names, $group->name);
        }
    }
    return tanvas_get_authority_string($names, null);
}

/**
 *  Term functions
 */

function tanvas_term_get_required_tiers($term) {
    $_procedure = "TERM_REQUIRED_TIERS: ";
    $required_tiers = array();
    
    //TODO: this
    
    if (TANVAS_DEBUG) error_log($_procedure . "required_tiers: " . serialize($required_tiers));
    
    return $required_tiers;
}

function tanvas_term_tiers_visibility($term) {
    
    //TODO: this
    return true;
}

function tanvas_term_get_required_memberships($term) {
    $_procedure = 'TERM_REQUIRED_MEMBERSHIPS: ';
    
    //Membership Integration
    $required_memberships = array();
    if (property_exists($term, 'term_id') and property_exists($term, 'taxonomy') and $term->taxonomy == 'product_cat') {
        $term_id = $term->term_id;
        
        if (class_exists('WC_Memberships')) {
            
            // get required membership plan
            $possible_membership_plans = array();
            if (function_exists('wc_memberships_get_membership_plans')) {
                $possible_membership_plans = wc_memberships_get_membership_plans();
            }
            foreach ($possible_membership_plans as $plan) {
                error_log($_procedure . "processing plan | " . $plan->get_name());
                $product_restriction_rules = $plan->get_product_restriction_rules();
                $rules_contain_term = false;
                foreach ($product_restriction_rules as $rule) {
                    if ($rule->get_content_type() == 'taxonomy') {
                        $rule_id = $rule->get_id();
                        if (TANVAS_DEBUG) error_log($_procedure . " -> Taxonomy Rule: " . $rule_id);
                        $rule_applies_to = $rule->get_object_ids();
                        if (TANVAS_DEBUG) error_log($_procedure . " -> applies to: " . serialize($rule_applies_to));
                        
                        //determine if rule applies to this term or parents
                        $this_id = $term_id;
                        do {
                            $term = get_term_by('id', $this_id, 'product_cat');
                            if (TANVAS_DEBUG) error_log($_procedure . " --> does it apply to term? : " . $this_id);
                            if (in_array(strval($this_id), $rule_applies_to)) {
                                if (TANVAS_DEBUG) error_log($_procedure . " ---> term in list");
                                $rules_contain_term = true;
                                break;
                            }
                            if ($rule->applies_to_single_object($this_id)) {
                                if (TANVAS_DEBUG) error_log($_procedure . " ---> applies to this category");
                                $rules_contain_term = true;
                                break;
                            }
                            $this_id = $term->parent;
                        }
                        while ($this_id);
                    }
                }
                if ($rules_contain_term) {
                    $required_memberships[] = $plan;
                }
            }
        }
    } 
    if (TANVAS_DEBUG) error_log($_procedure . "required memberships:");
    if ($required_memberships) foreach ($required_memberships as $membership) {
        if (TANVAS_DEBUG) error_log($_procedure . " -> " . serialize($membership->id) . " | " . serialize($membership->name));
    }
    
    // if(TANVAS_DEBUG) error_log($_procedure."required_memberships: ".serialize($required_memberships));
    
    return $required_memberships;
}

function tanvas_get_term_memberships_visibility($term) {
    $_procedure = 'TERM_MEMBERSHIPS_VISIBILILTY: ';
    
    if (!property_exists($term, 'taxonomy') || $term->taxonomy !== 'product_cat') {
        return true;
    }
    return current_user_can('wc_memberships_view_restricted_product_taxonomy_term', 'product_cat', $term->term_id);
}

function tanvas_term_get_required_groups($term) {
    $_procedure = 'TERM_REQUIRED_GROUPS: ';
    
    $required_caps = array();
    if (property_exists($term, 'term_id')) {
        $term_id = $term->term_id;
        
        if (class_exists('Groups_Restrict_Categories')) {
            $required_caps = Groups_Restrict_Categories::get_term_read_capabilities($term_id);
        }
    }
    
    if (TANVAS_DEBUG) error_log($_procedure . "required_caps: " . serialize($required_caps));
    
    return $required_caps;
}

function tanvas_term_groups_visible($term) {
    $_procedure = 'TERM_GROUPS_VISIBILITY: ';
    
    $visibility = true;
    if (property_exists($term, 'term_id')) {
        $term_id = $term->term_id;
        
        if (class_exists('Groups_Restrict_Categories')) {
            $user_id = get_current_user_id();
            $visibility = Groups_Restrict_Categories::user_can_read_term($user_id, $term_id);
        }
    }
    return $visibility;
}

function tanvas_term_messages() {
    $_procedure = 'TERM_MESSAGES: ';
    
    $messages = false;
    
    $user = wp_get_current_user();
    $user_tiers = tanvas_get_user_tiers($user);
    $user_groups = tanvas_get_user_groups($user);
    $user_memberships = tanvas_get_user_memberships($user);
    $user_authority = array();
    if ($user_tiers) {
        $user_authority = $user_tiers;
    } 
    elseif ($user_groups) {
        $user_authority = $user_groups;
    } 
    elseif ($user_memberships) {
        $user_authority = $user_memberships;
    }
    
    if (is_tax()) {
        if (TANVAS_DEBUG) error_log($_procedure . "is_tax() is true");
        
        global $wp_query;
        $term = $wp_query->get_queried_object();
        $term_name = $term->name;
        $taxonomy = $term->taxonomy;
        
        if (TANVAS_DEBUG) error_log($_procedure . "current term: " . serialize($taxonomy) . " | " . serialize($term_name));
        
        if (!$messages) {
            $required_tiers = tanvas_term_get_required_tiers($term);
            if ($required_tiers and !tanvas_term_visible($term)) {
                $messages = tanvas_display_tier_warnings($required_tiers, $user_tiers, 'term');
            }
        }
        
        if (!$messages and $taxonomy == 'product_cat') {
            $required_memberships = tanvas_term_get_required_memberships($term);
            if ($required_memberships and !tanvas_get_term_memberships_visibility($term)) {
                $messages = tanvas_display_user_membership_warnings($required_memberships, $user_memberships, 'term');
            }
        }
        
        if (!$messages) {
            $required_caps = tanvas_term_get_required_groups($term);
            if ($required_caps and !tanvas_term_groups_visible($term)) {
                $messages = tanvas_display_group_warnings($required_caps, $user_groups, 'term');
            }
        }
        if (!$messages) {
             //no warnings so far
            if (TANVAS_DEBUG) error_log($_procedure . "no warnings so far");
            tanvas_display_unrestricted_warning($user_authority);
        }
    } 
    else {
        if (TANVAS_DEBUG) error_log($_procedure . "taxonomy not being displayed");
    }
}

/**
 *  Post functions
 */

function tanvas_post_get_required_tiers($_product = null) {
    $_procedure = "POST_REQUIRED_TIERS: ";
    $required_tiers = array();
    if (!$_product) {
        global $product;
        $_product = $product;
    }
    if (class_exists('Lasercommerce_Plugin')) {
        global $Lasercommerce_Plugin;
        if (isset($Lasercommerce_Plugin)) {
            $purchase_tierIDs = $Lasercommerce_Plugin->maybeGetPurchaseTierIDs(array(), $_product);
            $required_tiers = $Lasercommerce_Plugin->tree->getTiers(array_reverse($purchase_tierIDs));
        }
    }
    if (TANVAS_DEBUG) error_log($_procedure . "required_tiers: " . serialize($required_tiers));
    return $required_tiers;
}

function tanvas_post_tier_visible($_product = null) {
    
    //TODO: make this more general for posts
    $_procedure = "POST_VISIBLE_TIERS: ";
    
    $visible = true;
    if (!$_product) {
        global $product;
        $_product = $product;
    }
    if (class_exists('Lasercommerce_Plugin')) {
        global $Lasercommerce_Plugin;
        if (isset($Lasercommerce_Plugin)) {
            $visible = $Lasercommerce_Plugin->maybeIsPurchasable(false, $_product);
        }
    } 
    else {
        if (TANVAS_DEBUG) error_log($_procedure . "LC plugin nonexistent");
    }
    if (TANVAS_DEBUG) error_log($_procedure . "visible: " . serialize($visible));
    
    return $visible;
}

function tanvas_post_get_required_memberships($_post) {
    $_procedure = "POST_REQUIRED_MEMBERSHIPS: ";
    
    //TODO: make this more general for posts
    $required_memberships = array();
    
    //TODO: THIS
    if (TANVAS_DEBUG) error_log($_procedure . "required_memberships: " . serialize($required_memberships));
    return $required_memberships;
}

function tanvas_post_memberships_visible($_post) {
    $_procedure = "POST_VISIBLE_MEMBERSHIPS: ";
    $visible = true;
    
    //TODO: this
    if (TANVAS_DEBUG) error_log($_procedure . "visible: " . serialize($visible));
    return $visible;
}

function tanvas_post_get_required_caps($_post) {
    $_procedure = "POST_REQUIRED_CAPS: ";
    
    //TODO: make this more general for posts
    $required_caps = array();
    if (property_exists($_post, 'id')) {
        $product_id = $_post->id;
        if (class_exists('Groups_Post_Access')) {
            $required_caps = Groups_Post_Access::get_read_post_capabilities($product_id);
        }
    }
    if (TANVAS_DEBUG) error_log($_procedure . "required_caps: " . serialize($required_caps));
    return $required_caps;
}

function tanvas_post_groups_visible($_post) {
    $_procedure = "POST_VISIBLE_GROUPS: ";
    $visible = true;
    
    //TODO: this
    if (TANVAS_DEBUG) error_log($_procedure . "visible: " . serialize($visible));
    return $visible;
}

function tanvas_post_warning() {
    $_procedure = 'POST_WARNINGS: ';
    
    $messages = false;
    
    $user = wp_get_current_user();
    $user_tiers = tanvas_get_user_tiers($user);
    $user_groups = tanvas_get_user_groups($user);
    $user_memberships = tanvas_get_user_memberships($user);
    $user_authority = array();
    if ($user_tiers) {
        $user_authority = $user_tiers;
    } 
    elseif ($user_groups) {
        $user_authority = $user_groups;
    } 
    elseif ($user_memberships) {
        $user_authority = $user_memberships;
    }
    
    if (is_single()) {
        if (TANVAS_DEBUG) error_log($_procedure . "is_single() is true");
        
        if (is_product()) {
            global $product;
            $_post = $product;
            
            if (!$messages) {
                $required_tiers = tanvas_post_get_required_tiers($_post);
                if ($required_tiers and !tanvas_post_tier_visible($_post)) {
                    $messages = tanvas_display_tier_warnings($required_tiers, $user_tiers, 'product');
                }
            }
        } 
        else {
            global $post;
            $_post = $post;
        }
        
        if (!$messages) {
            $required_caps = tanvas_post_get_required_caps($_post);
            if ($required_caps and !tanvas_post_groups_visible($_post)) {
                $messages = tanvas_display_group_warnings($required_caps, $user_groups, 'product');
            }
        }
        
        //Membership Integration
        if (!$messages) {
            $required_memberships = tanvas_post_get_required_memberships($_post);
            if ($required_memberships and !tanvas_post_memberships_visible()) {
                $messages = tanvas_display_user_membership_warnings($required_memberships, $user_memberships, 'product');
            }
        }
        
        if (!$messages) {
             //no warnings so far
            if (TANVAS_DEBUG) error_log($_procedure . "no warnings so far");
            tanvas_display_unrestricted_warning($user_authority);
        }
    } 
    else {
        error_log($_procedure . "single post not being displayed");
    }
}

add_action('woocommerce_before_shop_loop', 'tanvas_term_messages', 10);

// add_action( 'woocommerce_archive_description', 'tanvas_term_messages', 15 );

add_action('woocommerce_before_single_product', 'tanvas_post_warning', 7);
?>