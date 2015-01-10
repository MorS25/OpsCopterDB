<?php

namespace OpsCopter\DB\ProjectBundle\Entity;

interface RemoteProjectInterface {

    /**
     * Determine whether the given URI is handled by this project type.
     *
     * @param $uri
     * @return mixed
     */
    public static function isValidUri($uri);


    /**
     * Given a URI for this provider, normalize it so it matches a known pattern.
     *
     * @param $uri
     * @return mixed
     */
    public static function normalizeUri($uri);
}
