<?php

/**
 *  Buttons
 */

function tanvas_get_button($link, $text) {
    return '[button link="' . $link . '" class="tanvas"]' . __($text, TANVAS_DOMAIN) . '[/button]';
}

function tanvas_get_help_button() {
    return tanvas_get_button('/my-account/help', 'Help');
}

function tanvas_get_login_button() {
    return tanvas_get_button(wp_login_url(), 'Log In');
}

function tanvas_get_trade_login_button() {
    //TODO: This
    return tanvas_get_button(wp_login_url(), 'Log In');
}

function tanvas_get_register_button() {
    return tanvas_get_button(wp_registration_url(), 'Register');
}

function tanvas_get_continue_shopping_button() {
    return tanvas_get_button('/shop', 'Continue Shopping');
}

function tanvas_get_wholesale_application_button($name = null) {
    return tanvas_get_button('/my-account/request-wholesale/' . ($name ? '?name=' . esc_attr($name) : ''), 'Trade Application');
}

function tanvas_get_distributor_application_button($name = null) {
    //TODO: get link
    return tanvas_get_button('#', 'Distributor Application');
}

function tanvas_get_membership_application_button() {
    //TODO: get link
    return tanvas_get_button('#', 'Membership Application');
}

function tanvas_highlight_warning_keyword($keyword){
    return "<span class='tanvas-warning-highlight'>$keyword</span>" ;
}

function tanvas_get_warning_buttons($authorized = null) {
    $help_button = tanvas_get_help_button();
    $buttons = array($help_button);
    if (!$authorized) {
        if (!is_user_logged_in()) {
            $login_button = tanvas_get_login_button();
            array_push($buttons, $login_button);
            $register_button = tanvas_get_register_button();
            array_push($buttons, $register_button);
            
        }
    }
    return $buttons;
}

/**
 *  Warning Display Functions
 */

    //code inspired by https://github.com/Kaivosukeltaja/php-indefinite-article/blob/master/IndefiniteArticle.class.php
global $tanvas_indef_A_abbrev, $tanvas_indef_A_y_cons, $tanvas_indef_A_explicit_an, $tanvas_indef_A_ordinal_an, $tanvas_indef_A_ordinal_a;

$tanvas_indef_A_abbrev = "(?! FJO | [HLMNS]Y.  | RY[EO] | SQU
          | ( F[LR]? | [HL] | MN? | N | RH? | S[CHKLMNPTVW]? | X(YL)?) [AEIOU])
            [FHLMNRSX][A-Z]
        ";
$tanvas_indef_A_y_cons = 'y(b[lor]|cl[ea]|fere|gg|p[ios]|rou|tt)';
$tanvas_indef_A_explicit_an = "euler|hour(?!i)|heir|honest|hono";
$tanvas_indef_A_ordinal_an = "[aefhilmnorsx]-?th";
$tanvas_indef_A_ordinal_a = "[bcdgjkpqtuvwyz]-?th";

function tanvas_indefinite_article($input){
    global $tanvas_indef_A_abbrev, $tanvas_indef_A_y_cons, $tanvas_indef_A_explicit_an, $tanvas_indef_A_ordinal_an, $tanvas_indef_A_ordinal_a;
    $word = preg_replace("^\s*(.*)\s*^", "$1", $input);
    if(preg_match("/^[8](\d+)?/", $word)) {
        return "an $word";
    }
    if(preg_match("/^[1][1](\d+)?/", $word) || (preg_match("/^[1][8](\d+)?/", $word))) {
        if(strlen(preg_replace(array("/\s/", "/,/", "/\.(\d+)?/"), '', $word))%3 == 2) {
            return "an $word";
        }
    }
    if(preg_match("/^(".$tanvas_indef_A_ordinal_a.")/i", $word))       return "a $word";
    if(preg_match("/^(".$tanvas_indef_A_ordinal_an.")/i", $word))      return "an $word";
    if(preg_match("/^(".$tanvas_indef_A_explicit_an.")/i", $word))         return "an $word";
    if(preg_match("/^[aefhilmnorsx]$/i", $word))        return "an $word";
    if(preg_match("/^[bcdgjkpqtuvwyz]$/i", $word))      return "a $word";
    if(preg_match("/^(".$tanvas_indef_A_abbrev.")/x", $word))          return "an $word";
    if(preg_match("/^[aefhilmnorsx][.-]/i", $word))         return "an $word";
    if(preg_match("/^[a-z][.-]/i", $word))          return "a $word";
    if(preg_match("/^[^aeiouy]/i", $word))                  return "a $word";
    if(preg_match("/^e[uw]/i", $word))                      return "a $word";
    if(preg_match("/^onc?e\b/i", $word))                    return "a $word";
    if(preg_match("/^uni([^nmd]|mo)/i", $word))     return "a $word";
    if(preg_match("/^ut[th]/i", $word))                     return "an $word";
    if(preg_match("/^u[bcfhjkqrst][aeiou]/i", $word))   return "a $word";
    if(preg_match("/^U[NK][AIEO]?/", $word))                return "a $word";
    if(preg_match("/^[aeiou]/i", $word))            return "an $word";
    if(preg_match("/^(".$tanvas_indef_A_y_cons.")/i", $word))  return "an $word";
    return "a $word";
}

function tanvas_get_warning_string($warning_type, $message, $instructions, $buttons) {
    if (!$warning_type) {
        $warning_type = 'alert';
    }
    $warning_type = 'info'; //override all boxes as info
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
        $lines[] = implode(
            ' ', 
            array_filter($buttons)
        );
    }
    return '[box type="' . $warning_type . '"]' . implode('<br/>', $lines) . '[/box]';
}

function tanvas_authorities_contain($authorities, $needle){
    $_procedure = "AUTHORITIES_CONTAIN|$needle: ";
    $auth_sting = strtolower(tanvas_get_authority_string($authorities));
    if(TANVAS_DEBUG) error_log($_procedure."$auth_sting");
    if(strpos($auth_sting, strtolower($needle) ) !== false){
        return true;
    }
    return false;
}

function tanvas_is_wholesale_required($required_authorities){
    return tanvas_authorities_contain($required_authorities, 'Wholesale');
}

function tanvas_is_distributor_required($required_authorities){
    return tanvas_authorities_contain($required_authorities, 'Distributor');
}

function tanvas_is_user_tier($user = null, $tier){
    if (!$user) {
        $user = wp_get_current_user();
    }

    if (class_exists('Lasercommerce_Plugin')) {
        global $Lasercommerce_Plugin;
        if (isset($Lasercommerce_Plugin)) {
            $visible_tiers = $Lasercommerce_Plugin->tree->getVisibleTiers($user);
            return tanvas_authorities_contain($visible_tiers, $tier);
        }
    }
    return false;
}

function tanvas_is_user_wholesale($user = null){
    return tanvas_is_user_tier($user, 'wholesale');
}

function tanvas_is_user_distributor($user = null){
    return tanvas_is_user_tier($user, 'distributor');
}

// function tanvas_is_user_wholesale($user_authorities){
//     //TODO: this properly
//     return tanvas_authorities_contain($user_authorities, 'Wholesale');
// }

function tanvas_indefinite_authority($authority, $authority_type){
    $indef_article = tanvas_indefinite_article(sprintf("%s %s", $authority, $authority_type));
    return preg_replace("/(a|an) ([\w\s]*)/", "$1 ".tanvas_highlight_warning_keyword("$2"), $indef_article);
}

function tanvas_get_tier_warning_message($authority, $authority_type = 'customer'){
    if(is_user_logged_in()){
        $message = __('You are logged in as %s.', TANVAS_DOMAIN);
    } else {
        $message = __('You are viewing our site as %s.', TANVAS_DOMAIN);
    }
    return sprintf(
        $message, 
        tanvas_indefinite_authority($authority, $authority_type)
    );
}

function tanvas_get_memberships_warning_message($required_authority, $action, $authority_type = 'member'){
    if(is_user_logged_in()){
        $message = __('You are not %s and cannot %s.', TANVAS_DOMAIN);
    } else {
        $message = __('You are not logged in as %s and cannot %s.', TANVAS_DOMAIN);
    }
    return sprintf(
        $message, 
        tanvas_indefinite_authority($required_authority, $authority_type), 
        $action
    );
}

function tanvas_get_professional_warning_message($required_authority, $object_type, $old_message, $visible = false){
    if($visible){
        return __("The pricing shown below is for ".tanvas_highlight_warning_keyword("retail customers")." only.", TANVAS_DOMAIN);
    } else {
        if(in_array($object_type, array("product_cat"))){
            $message = __("These products are only available for purchase by %s.", TANVAS_DOMAIN);
        } elseif(in_array($object_type, array("category", "cat"))){
            $message = __("This category is only available for %s.", TANVAS_DOMAIN);
        }elseif(in_array($object_type, array("product"))){
            $message = __("This product is only available for purchase by %s.", TANVAS_DOMAIN);
        }elseif(in_array($object_type, array("post", "page"))){
            $message = __("This $object_type is only available for %s.", TANVAS_DOMAIN);
        }else{
            return $old_message;
        }
        return sprintf(
            $message, 
            sprintf( tanvas_highlight_warning_keyword("%s %s"), $required_authority, "customers")
        );
    }
}

function tanvas_get_action_string($object_type){
    if(in_array($object_type, array("product_cat"))){
        return __("purchase these products", TANVAS_DOMAIN );
    }elseif(in_array($object_type, array("category", "cat"))){
        return __("view this category", TANVAS_DOMAIN );
    }elseif(in_array($object_type, array("product"))){
        return __("purchase this product", TANVAS_DOMAIN);
    }elseif(in_array($object_type, array("post", "page"))){
        return __("view this $object_type", TANVAS_DOMAIN);
    }
    return __("view this item", TANVAS_DOMAIN);
}

function tanvas_get_unrestricted_action_string($object_type){
    if(in_array($object_type, array("product_cat", "product", ""))){
        return __("view additional pricing information", TANVAS_DOMAIN );
    } else {
        return __("view restricted content", TANVAS_DOMAIN);
    }
}

function tanvas_get_industry_instructions($visible = false){
    $instructions = __("If you are an industry professional, please %s", TANVAS_DOMAIN);
    $instructions .= $visible ? __(" as discounts apply.", TANVAS_DOMAIN) : "." ;
    return $instructions;
}

function tanvas_get_trade_instructions($visible = false){
    $instructions = __('To view '.tanvas_highlight_warning_keyword("trade pricing").', please %s', TANVAS_DOMAIN);
    return $instructions;
}

// function tanvas_display_star_warinigs($star, $required_authorities, $user_authorities, $object_type, $visible = false){
//     $_procedure = "DISPLAY_STAR_WARN|$star";

//     $authority = tanvas_get_tier_authority($user_tiers);
//     $required_authority = tanvas_get_tier_authority($required_tiers);
//     $buttons = array();
//     $action = tanvas_get_action_string($object_type);

//     if($required_authorities){

//     } else {

//     }
// }

function tanvas_display_tier_warnings($required_authorities, $user_authorities, $object_type, $visible = false, $echo=true) {
    $_procedure = "DISPLAY_TIER_WARN: ";
    if ($required_authorities) {
        $buttons = array();
        $authority = tanvas_get_tier_authority($user_authorities);
        $required_authority = tanvas_get_tier_authority($required_authorities);
        $message = tanvas_get_tier_warning_message($authority);
        $help_button = tanvas_get_help_button();
        $display_message = false;

        if(!$visible){
            $display_message = true;
            $box_type = 'alert';
            $action = tanvas_get_action_string($object_type);
            $login_button = tanvas_get_login_button();
            $register_button = tanvas_get_register_button();
            $instructions = __("Please %s to %s", TANVAS_DOMAIN);
            $required_action = "apply for %s";
            
            if(tanvas_is_wholesale_required($required_authorities)){
                $login_button = tanvas_get_trade_login_button();
                $register_button = tanvas_get_wholesale_application_button();
                // $required_authority = "wholesale"; 
                $required_authority = "trade"; 
                // $instructions = __("Wholesale (trade) customers can %s to %s", TANVAS_DOMAIN);
                // $instructions = tanvas_get_industry_instructions($visible);
                $instructions = tanvas_get_trade_instructions($visible);
                $message = tanvas_get_professional_warning_message($required_authority, $object_type, $message);
                $action = "";
            } elseif (tanvas_is_distributor_required($required_authorities) ) {
                $login_button = tanvas_get_trade_login_button();
                $register_button = tanvas_get_distributor_application_button();
                $required_authority = "distributor";
                // $instructions = __("Distributor customers can %s to %s", TANVAS_DOMAIN);
                // $instructions = tanvas_get_industry_instructions($visible);
                $instructions = tanvas_get_trade_instructions($visible);
                $message = tanvas_get_professional_warning_message($required_authority, $object_type, $message);
                $action = "";
            } 

            if (!is_user_logged_in()) {
                $required_action = __("log in or apply for %s", TANVAS_DOMAIN);
                array_push($buttons, $login_button);
            }

            $required_action = sprintf($required_action, tanvas_indefinite_authority( $required_authority, 'account' ));
            // $required_action = sprintf($required_action, 'a <span class="tanvas-warning-highlight">trade account</span>');

            if($action){
                $instructions = sprintf($instructions, $required_action, $action);
            } else {
                $instructions = sprintf($instructions, $required_action);
            }

            array_push($buttons, $register_button);
        } else {
            $box_type = 'tick';
            
            if(tanvas_is_wholesale_required($required_authorities)){
                $display_message = true;
                // $required_authority = "wholesale"; 
                $required_authority = "trade"; 
                $instructions = sprintf(__("You can view %s restricted items on this page"), $required_authority);
            } elseif(tanvas_is_distributor_required($required_authorities)){
                $display_message = true;
                $required_authority = "distributor";
                $instructions = sprintf(__("You can view %s restricted items on this page"), $required_authority);
            } else {
                // $required_authority = "wholesale";
                $required_authority = "trade";
                if(tanvas_is_user_wholesale()){
                    $display_message = true;
                    $instructions = sprintf(__("You can view %s restricted items on this page"), $required_authority);
                } else {
                    $box_type = 'info';
                    $display_message = true;
                    $register_button = tanvas_get_wholesale_application_button();
                    array_push($buttons, $register_button);
                    $action = tanvas_get_unrestricted_action_string($object_type);
                    $required_action = __("apply for %s", TANVAS_DOMAIN);
                    $required_action = sprintf($required_action, tanvas_indefinite_authority( $required_authority, 'account' ));
                    // $required_action = sprintf($required_action, 'a <span class="tanvas-warning-highlight">trade account</span>');
                    $instructions = sprintf( __("Please %s to %s", TANVAS_DOMAIN), $required_action, $action);
                }
            }
        }

        array_push($buttons, $help_button);

        $message_string = do_shortcode(tanvas_get_warning_string($box_type, $message, $instructions, $buttons));
        if($echo){
            echo $message_string;
        } else {
            return $message_string;
        }
        
        return true;
    } 
    else {
        error_log($_procedure . "no required tiers");
        return false;
    }
}

function tanvas_display_user_membership_warnings($required_authorities, $user_authorities, $object_type, $visible = false, $echo=true) {
    $_procedure = 'DISPLAY_MEMBERSHIP_WARN: ';
    if ($required_authorities) {
        $buttons = tanvas_get_warning_buttons();
        $required_authority = tanvas_get_memberships_authority(array_slice($required_authorities, 0, 1));
        $box_type = "";

        if(tanvas_is_wholesale_required($required_authorities)){
            $upgrade_button = tanvas_get_wholesale_application_button();
            $instructions = __("Please apply for a Wholesale plan to continue.", TANVAS_DOMAIN);
            array_push($buttons, $upgrade_button);
        } elseif (tanvas_is_distributor_required($required_authorities) ) {
            $instructions = __("Please apply for a Distributor plan to continue.", TANVAS_DOMAIN);
            $upgrade_button = tanvas_get_distributor_application_button();
            array_push($buttons, $upgrade_button);
        } else {
            if (is_user_logged_in()) {
                $instructions = __("Please apply for a $required_authority plan or continue shopping.", TANVAS_DOMAIN);
            } else {
                $instructions = __("Please log in with a $required_authority plan.", TANVAS_DOMAIN);
            }
        }
        if (is_user_logged_in()) {
            if($user_authorities){
                $authority = tanvas_get_memberships_authority($user_authorities);
                $message = __('This item is restricted because you are viewing our site at ', TANVAS_DOMAIN) . ' ' . $authority . '.';
            } else {
                $message = __('This item is restricted because you do not have any active membership plans.', TANVAS_DOMAIN);
            }
            $buttons[] = tanvas_get_continue_shopping_button();
        } 
        else {
            $message = __('This item is restricted because you are not logged in.');
        }
        
        $message_string = do_shortcode(tanvas_get_warning_string($box_type, $message, $instructions, $buttons));
        if($echo){
            echo $message_string;
        } else {
            return $message_string;
        }

        return true;
    } 
    else {
        error_log($_procedure . "no required_memberships");
        return false;
    }
}

function tanvas_display_group_warnings($required_authorities, $user_authorities, $object_type, $visible = false, $echo=true) {
    $_procedure = 'DISPLAY_GROUP_WARN: ';
    if ($required_authorities) {
        $buttons = tanvas_get_warning_buttons();
        $authority = tanvas_get_memberships_authority($user_authorities);
        $required_authority = tanvas_get_memberships_authority(array_slice($required_authorities, 0, 1));
        $first_group = $required_authorities[0];
        $box_type = "";
        
        if(tanvas_is_wholesale_required($required_authorities)){
            $upgrade_button = tanvas_get_wholesale_application_button();
            array_push($buttons, $upgrade_button);
        } elseif (tanvas_is_distributor_required($required_authorities) ) {
            $upgrade_button = tanvas_get_distributor_application_button();
            array_push($buttons, $upgrade_button);
        }

        if (is_user_logged_in()) {
            $message = __('You are viewing our site as a ', TANVAS_DOMAIN) . " $authority member.";
            $required_authority = tanvas_get_group_authority($required_authorities);
            $instructions = __("Please apply for $required_authority access or continue shopping.", TANVAS_DOMAIN);
            array_push($buttons, tanvas_get_continue_shopping_button());
        } else {
            $message = __('This item is restricted because you are not logged in.');
            $instructions = __("Please log in with $required_authority access.", TANVAS_DOMAIN);
        }
        
        $group_str = '"' . implode(', ', $required_authorities) . '"';

        $message_string = do_shortcode(
            '[groups_non_member group=' . $group_str . ']' . 
            tanvas_get_warning_string($box_type, $message, $instructions, $buttons) . 
            '[/groups_non_member]'
        );
        if($echo){
            echo $message_string;
        } else {
            return $message_string;
        }
        return true;
    } 
    else {
        error_log($_procedure . "no required_memberships");
        return false;
    }
}

function tanvas_display_unrestricted_warning($required_authorities, $user_authorities, $object_type) {
    $_procedure = "DISPLAY_UNRESTRICTED_WARN: ";
    if (TANVAS_DEBUG) error_log($_procedure . "start");
    
    $visible = true;
    $buttons = array();
    $authority = tanvas_get_authority_string($user_authorities);
    $message = tanvas_get_tier_warning_message($authority);
    $help_button = tanvas_get_help_button();    
    $display_message = false;
    $instructions = "";

    if( !tanvas_is_user_wholesale()){
        if (TANVAS_DEBUG) error_log($_procedure . "user is not wholesale");

        $action = tanvas_get_unrestricted_action_string($object_type);
        $box_type = 'info';
        $register_button = tanvas_get_wholesale_application_button();
        $login_button = tanvas_get_trade_login_button();
        if(in_array($object_type, array('product', 'product_cat'))){
            if (TANVAS_DEBUG) error_log($_procedure . "object is wc product or cat");

            $display_message = true;
            // $required_authority = "wholesale"; 
            $required_authority = "trade"; 
            // $instructions = __("Wholesale (trade) customers can %s to %s", TANVAS_DOMAIN);
            $message = tanvas_get_professional_warning_message($required_authority, $object_type, $message, true);
            // $instructions = tanvas_get_industry_instructions(true);
            $instructions = tanvas_get_trade_instructions(true);
            $action = "";

            $required_action = __("apply for %s", TANVAS_DOMAIN);
            if (!is_user_logged_in()) {
                $required_action = __("log in or apply for %s", TANVAS_DOMAIN);
                array_push($buttons, $login_button);
            }

        } elseif(!is_user_logged_in()) {
            if (TANVAS_DEBUG) error_log($_procedure . "user is not logged in");
            $display_message = true;
            // $instructions = __("Please %s to %s", TANVAS_DOMAIN);
            $required_action = __("log in or apply for %s", TANVAS_DOMAIN);
            array_push($buttons, $login_button);
        } 
        array_push($buttons, $register_button);
        $required_action = sprintf($required_action, tanvas_indefinite_authority( $required_authority, 'account' ));
        // $required_action = sprintf($required_action, 'a <span class="tanvas-warning-highlight">trade account</span>');
        if($action){
            $instructions = sprintf($instructions, $required_action, $action);
        } else {
            $instructions = sprintf($instructions, $required_action);
        }
    } else {
        if (TANVAS_DEBUG) error_log($_procedure . "user is wholesale");
        $box_type = 'tick';
        $display_message = true;
    }

    array_push($buttons, $help_button);

    if($display_message){
        echo do_shortcode(
            tanvas_get_warning_string($box_type, $message, $instructions, $buttons) 
        );
    }
    if (TANVAS_DEBUG) error_log($_procedure . "end");
    return $display_message;
}

/**
 *  Authority functions
 */

function tanvas_get_authority_string($authorities, $default = 'Public') {
    $names = array();
    if ($authorities) {
        foreach($authorities as $authority){
            if (!property_exists($authority, 'name')) continue;
            $name = $authority->name;
            if($name){
                $names[] = $name;
            }
        }
    } 
    if($names){
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

function tanvas_get_user_tier_ids($user = null){
    $tiers = tanvas_get_user_tiers($user);
    if ( class_exists('Lasercommerce_Plugin') ){
        global $Lasercommerce_Plugin;
        if(isset($Lasercommerce_Plugin)){
            return $Lasercommerce_Plugin->tree->getTierIds($tiers);
        }
    }
    return array();
}

function tanvas_get_user_visible_tiers($user = null){
    $_procedure = 'GET_USER_VISIBLE_TIERS: ';
    
    $tiers = array();
    if (!$user) {
        $user = wp_get_current_user();
    }
    
    $user_id = $user->ID;
    
    if (class_exists('Lasercommerce_Plugin')) {
        global $Lasercommerce_Plugin;
        if (isset($Lasercommerce_Plugin)) {
            $tiers = $Lasercommerce_Plugin->tree->getVisibleTiers($user_id);
        }
    } 
    else {
        if (TANVAS_DEBUG) error_log($_procedure . "LC plugin class DNE");
    }
    
    if (TANVAS_DEBUG) error_log($_procedure . "tiers: " . serialize($tiers));
    return $tiers;
}

function tanvas_get_user_visible_tier_ids($user = null){
    $tiers = tanvas_get_user_visible_tiers($user);
    if ( class_exists('Lasercommerce_Plugin') ){
        global $Lasercommerce_Plugin;
        if(isset($Lasercommerce_Plugin)){
            return $Lasercommerce_Plugin->tree->getTierIds($tiers);
        }
    }    
    return array();
}

function tanvas_get_tier_authority($tiers = array()) {
    $public_authority = 'Retail';
    return tanvas_get_authority_string($tiers, $public_authority);
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
    return tanvas_get_authority_string($memberships);
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
    return tanvas_get_authority_string($groups);
}

/**
 *  Term functions
 */

function tanvas_term_restricted($term, $user = null){
    if (!$user) {
        $user = wp_get_current_user();
    }
    
    $user_id = $user->ID;
    $term_id = $term->term_id; 

    if(class_exists('Lasercommerce_Plugin')){
        global $Lasercommerce_Plugin;
        return !$Lasercommerce_Plugin->visibility->user_can_read_term($user_id, $term_id);
    } else {
        return false;
    }
}

function tanvas_term_get_required_tiers($term, $user = null) {
    $_procedure = "TERM_REQUIRED_TIERS: ";
    $required_tiers = array();

    if (!$user) {
        $user = wp_get_current_user();
    }
    
    $user_id = $user->ID;
    $term_id = $term->term_id; 
    
    if (class_exists('Lasercommerce_Plugin')) {
        global $Lasercommerce_Plugin;
        if (isset($Lasercommerce_Plugin)) {
            $required_tierIDs = array();
            $this_id = $term_id; 
            do{
                if (TANVAS_DEBUG) error_log($_procedure . " -> any requirements for term? : " . $this_id);
                $term = get_term_by('id', $this_id, 'product_cat');
                $this_required_tierIDs = $Lasercommerce_Plugin->visibility->get_term_read_tiers($this_id);
                if($this_required_tierIDs){
                    if (TANVAS_DEBUG) error_log($_procedure . " --> yes " . serialize($this_required_tierIDs) );
                    $required_tierIDs = array_unique(array_merge($this_required_tierIDs, $required_tierIDs));
                } else {
                    if (TANVAS_DEBUG) error_log($_procedure . " --> no "  );
                }
                $this_id = $term->parent;
            } while($this_id);
            if($required_tierIDs){
                $required_tiers = $Lasercommerce_Plugin->tree->getTiers($required_tierIDs);
            }
        }
    }
    
    if (TANVAS_DEBUG) error_log($_procedure . "required_tiers: " . serialize($required_tiers));
    
    return $required_tiers;
}

function tanvas_term_tiers_visibile($term, $user = null){
    if (!$user) {
        $user = wp_get_current_user();
    }
    
    $user_id = $user->ID;
    $term_id = $term->term_id; 

    if(class_exists('Lasercommerce_Plugin')){
        global $Lasercommerce_Plugin;
        $this_id = $term_id;
        do {
            $term = get_term_by('id', $this_id, 'product_cat');
            $this_visible = $Lasercommerce_Plugin->visibility->user_can_read_term($user_id, $this_id);
            if(!$this_visible) return false;
            $this_id = $term->parent;
        } while ($this_id);
    }
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

function tanvas_term_memberships_visible($term) {
    $_procedure = 'TERM_MEMBERSHIPS_VISIBILILTY: ';
    
    if (!property_exists($term, 'taxonomy') || $term->taxonomy !== 'product_cat') {
        return true;
    }

    //TODO: This only shows if the category is visible but ignores parents
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

    $required_tiers = array();
    $required_memberships = array();
    $required_caps = array();
    $required_authorities = array();
    
    if (is_tax()) {
        if (TANVAS_DEBUG) error_log($_procedure . "is_tax() is true");
        
        global $wp_query;
        $term = $wp_query->get_queried_object();
        $term_name = $term->name;
        $taxonomy = $term->taxonomy;
        
        if (TANVAS_DEBUG) error_log($_procedure . "current term: " . serialize($taxonomy) . " | " . serialize($term_name));
        
        if (!$messages) {
            $required_tiers = tanvas_term_get_required_tiers($term);
            if ($required_tiers){
                // if(!$required_authorities){
                //     $required_authorities = $required_tiers;
                // }
                if(!tanvas_term_tiers_visibile($term)){
                    $messages = tanvas_display_tier_warnings($required_tiers, $user_tiers, $taxonomy);
                }
            }
        }
        
        if (!$messages and $taxonomy == 'product_cat') {
            $required_memberships = tanvas_term_get_required_memberships($term);
            if ($required_memberships){
                // if(!$required_authorities){
                //     $required_authorities = $required_memberships;
                // }
                if(tanvas_term_memberships_visible($term)){
                    $messages = tanvas_display_user_membership_warnings($required_memberships, $user_memberships, $taxonomy);
                }
            }
        }
        
        if (!$messages) {
            $required_caps = tanvas_term_get_required_groups($term);
            if ($required_caps){
                // if(!$required_authorities){
                //     $required_authorities = $required_caps;
                // }
                if(!tanvas_term_groups_visible($term)){
                    $messages = tanvas_display_group_warnings($required_caps, $user_groups, $taxonomy);
                }
            }
        }

        if(!$messages and $required_tiers){
            $messages = tanvas_display_tier_warnings($required_tiers, $user_tiers, $taxonomy, true);
        }
        if(!$messages and $required_memberships){
            $messages = tanvas_display_user_membership_warnings($required_memberships, $user_memberships, $taxonomy, true);
        }
        if(!$messages and $required_caps){
            $messages = tanvas_display_group_warnings($required_caps, $user_groups, $taxonomy, true);
        }

        if (!$messages) {
             //no warnings so far
            if (TANVAS_DEBUG) error_log($_procedure . "no warnings so far");

            tanvas_display_unrestricted_warning($required_authorities, $user_authority, $taxonomy);
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
    $required_authorities = array();
    
    if (is_single()) {
        if (TANVAS_DEBUG) error_log($_procedure . "is_single() is true");
        
        if (is_product()) {
            global $product;
            $_post = $product;
            $item_type = 'product';
            
            if (!$messages) {
                $required_tiers = tanvas_post_get_required_tiers($_post);
                if ($required_tiers){
                    if(!$required_authorities) {
                        $required_authorities = $required_tiers;
                    }
                    if(!tanvas_post_tier_visible($_post)) {
                        $messages = tanvas_display_tier_warnings($required_tiers, $user_tiers, $item_type);
                    }
                }
            }
        } else {
            global $post;
            $_post = $post;
            $item_type = 'post';
        }
        
        //Membership Integration
        if (!$messages) {
            $required_memberships = tanvas_post_get_required_memberships($_post);
            if ($required_memberships){
                if(!$required_authorities){
                    $required_authorities = $required_memberships;
                } 
                if(!tanvas_post_memberships_visible()) {
                    $messages = tanvas_display_user_membership_warnings($required_memberships, $user_memberships, $item_type);
                }
            }
        }

        if (!$messages) {
            $required_caps = tanvas_post_get_required_caps($_post);
            if ($required_caps){
                if(!$required_authorities){
                    $required_authorities = $required_caps;
                }
                if(!tanvas_post_groups_visible($_post)) {
                    $messages = tanvas_display_group_warnings($required_caps, $user_groups, $item_type);
                }
            }
        }
        
        
        if (!$messages) {
             //no warnings so far
            if (TANVAS_DEBUG) error_log($_procedure . "no warnings so far");
            tanvas_display_unrestricted_warning($required_authorities, $user_authority, $item_type);
        }
    } 
    else {
        error_log($_procedure . "single post not being displayed");
    }
}

function tanvas_wholesale_content_restricted_shortcode($args, $content=""){
    $args = shortcode_atts( array(
        'object_type' => 'product_cat'
    ), $args);

    if (class_exists('Lasercommerce_Plugin')) {
        global $Lasercommerce_Plugin;
        if (isset($Lasercommerce_Plugin)) {
            $required_authorities = array($Lasercommerce_Plugin->tree->getWholesaleTier() );
        }
    }
    $user_authorities = tanvas_get_user_tiers();
    $object_type = $args['object_type'];
    $visible = tanvas_is_user_wholesale();
    $out = tanvas_display_tier_warnings($required_authorities, $user_authorities, $object_type, $visible, false);
    $out .= $content;
    return $out;
}

function tanvas_tier_restrict_content_shortcode($args, $content){
    $args = shortcode_atts( array(
        'tiers' => '',
        'hide_tiers' => '',
        'logged_in' => '',
    ), $args);

    // $out = '';

    $message_visible = true;
    if(isset($args['logged_in']) and ! empty($args['logged_in']) ){
        switch ($args['logged_in']) {
            case 'true':
                if( is_user_logged_in() ){
                    $message_visible = true;
                } else {
                    $message_visible = false;
                }
                break;
            case 'false':
                if( is_user_logged_in() ){
                    $message_visible = false;
                } else {
                    $message_visible = true;
                }
            default:
                break;
        }
    } 
    if($message_visible) {
        $user_tier_ids = tanvas_get_user_visible_tier_ids();
        // $out .= "user_tiers: ".serialize($user_tier_ids)."<br/>";
        if (isset($args['tiers']) and $args['tiers']){
            $message_visible = false;
            $required_tier_ids = explode(',', $args['tiers']);
            // $out .= "required: " . $args['tiers']."<br/>";
            if (class_exists('Lasercommerce_Plugin')) {
                global $Lasercommerce_Plugin;
                if (isset($Lasercommerce_Plugin)) {
                    // $message_visible = false;
                    // $required_tier_ids = array($Lasercommerce_Plugin->tree->getWholesaleTier() );
                    $message_visible = $Lasercommerce_Plugin->visibility->tier_ids_satisfy_requirement($user_tier_ids, $required_tier_ids);
                    // $out .= "visible: " . ($message_visible) ."<br/>";
                }
            }
        } elseif (isset($args['hide_tiers']) and $args['hide_tiers']) {
            $required_tier_ids = explode(',', $args['hide_tiers']);
            // $out .= "hide_tiers: " . $args['hide_tiers']."<br/>";
            if(class_exists('Lasercommerce_Plugin')) {
                global $Lasercommerce_Plugin;
                if(isset($Lasercommerce_Plugin)){
                    $message_visible = ! $Lasercommerce_Plugin->visibility->tier_ids_satisfy_requirement($user_tier_ids, $required_tier_ids);
                    // $out .= "visible: " . ($message_visible) . "<br/>";
                }
            }
        }
    }

    if( $message_visible){
        return do_shortcode($content);
        // return $out . do_shortcode($content);
    } else{
        return '';
        // return $out;
    }

}

add_action('woocommerce_before_shop_loop', 'tanvas_term_messages', 10);

// add_action( 'woocommerce_archive_description', 'tanvas_term_messages', 15 );

add_action('woocommerce_before_single_product', 'tanvas_post_warning', 7);

add_shortcode('tanvas_wholesale_content_restricted_message', 'tanvas_wholesale_content_restricted_shortcode');

add_shortcode('tanvas_tier_restrict_content', 'tanvas_tier_restrict_content_shortcode');

?>