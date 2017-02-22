<?php

namespace UserBundle\Model;

use UserBundle\Model\om\BaseUserTitle;

class UserTitle extends BaseUserTitle
{
    const MISTER_ID = 1;
    const MISTER = "TITLE.MISTER";
    const MISTER_SHORT = "TITLE.MISTER_SHORT";

    const MISS_ID = 2;
    const MISS = "TITLE.MISS";
    const MISS_SHORT = "TITLE.MISS_SHORT";

    const MISSES_ID = 3;
    const MISSES = "TITLE.MISSES";
    const MISSES_SHORT = "TITLE.MISSES_SHORT";

    /**
     * getTitleListArray()
     * @return mixed
     */
    static public function getTitleListArray()
    {
        $titles = UserTitleQuery::create()->find();

        $titleArr['title'] = $titles->toArray();
        return $titleArr;
    }

}
