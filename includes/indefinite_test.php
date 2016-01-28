    <?php
    //     #indefinite_test.php
    // global $indef_A_abbrev, $indef_A_y_cons, $indef_A_explicit_an, $indef_A_ordinal_an, $indef_A_ordinal_a;

    // $indef_A_abbrev = "(?! FJO | [HLMNS]Y.  | RY[EO] | SQU
    //           | ( F[LR]? | [HL] | MN? | N | RH? | S[CHKLMNPTVW]? | X(YL)?) [AEIOU])
    //             [FHLMNRSX][A-Z]
    //         ";
    // $indef_A_y_cons = 'y(b[lor]|cl[ea]|fere|gg|p[ios]|rou|tt)';
    // $indef_A_explicit_an = "euler|hour(?!i)|heir|honest|hono";
    // $indef_A_ordinal_an = "[aefhilmnorsx]-?th";
    // $indef_A_ordinal_a = "[bcdgjkpqtuvwyz]-?th";

    // function indefinite_article($input){
    //     global $indef_A_abbrev, $indef_A_y_cons, $indef_A_explicit_an, $indef_A_ordinal_an, $indef_A_ordinal_a;
    //     $word = preg_replace("^\s*(.*)\s*^", "$1", $input);
    //     if(preg_match("/^[8](\d+)?/", $word)) {
    //         return "an $word";
    //     }
    //     if(preg_match("/^[1][1](\d+)?/", $word) || (preg_match("/^[1][8](\d+)?/", $word))) {
    //         if(strlen(preg_replace(array("/\s/", "/,/", "/\.(\d+)?/"), '', $word))%3 == 2) {
    //             return "an $word";
    //         }
    //     }
    //     if(preg_match("/^(".$indef_A_ordinal_a.")/i", $word))       return "a $word";
    //     if(preg_match("/^(".$indef_A_ordinal_an.")/i", $word))      return "an $word";
    //     if(preg_match("/^(".$indef_A_explicit_an.")/i", $word))         return "an $word";
    //     if(preg_match("/^[aefhilmnorsx]$/i", $word))        return "an $word";
    //     if(preg_match("/^[bcdgjkpqtuvwyz]$/i", $word))      return "a $word";
    //     if(preg_match("/^(".$indef_A_abbrev.")/x", $word))          return "an $word";
    //     if(preg_match("/^[aefhilmnorsx][.-]/i", $word))         return "an $word";
    //     if(preg_match("/^[a-z][.-]/i", $word))          return "a $word";
    //     if(preg_match("/^[^aeiouy]/i", $word))                  return "a $word";
    //     if(preg_match("/^e[uw]/i", $word))                      return "a $word";
    //     if(preg_match("/^onc?e\b/i", $word))                    return "a $word";
    //     if(preg_match("/^uni([^nmd]|mo)/i", $word))     return "a $word";
    //     if(preg_match("/^ut[th]/i", $word))                     return "an $word";
    //     if(preg_match("/^u[bcfhjkqrst][aeiou]/i", $word))   return "a $word";
    //     if(preg_match("/^U[NK][AIEO]?/", $word))                return "a $word";
    //     if(preg_match("/^[aeiou]/i", $word))            return "an $word";
    //     if(preg_match("/^(".$indef_A_y_cons.")/i", $word))  return "an $word";
    //     return "a $word";
    // }

    // $words = array(
    //     "historical",
    //     "hour",
    //     "wholesale",
    //     "administrator",
    //     "inner circle",
    //     "honour",
    //     "helicpoter"
    // );
    // foreach ($words as $word) {
    //     echo indefinite_article($word);
    //     echo "\n";
    // }

// echo serialize(array_map('strtolower', array('WV', 'aBV')));

    ?>