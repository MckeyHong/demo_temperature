<?php
/**
 * api error Code
 *
 * @author mckey
 * @since 2016-12-22
 */

return [
    // 400
    'validateFail'    => 400002,
    
    // 401
    'invalidCredentials' => 401001,
    //    'otpError' => 401002,
    'invalidateTokenError' => 401003,
    
    // 403
    'groupNameRepeat' => 403003,
    'groupIsNotFound' => 403004,
    'userIsNotFound' => 403005,
    'groupHasUser' => 403006,
    'isSelf' => 403007,
    'hallIsNotFound' => 403008,
    'isAdmin' => 403009,
    'rowAffectedNotEqual1' => 403010,
    'isAdminGroup' => 403011,
    'adminNotChangeGroup' => 403012,
    'serialNumberCannotUse' => 403013,
    
    
    // 404
    'notFound' => 404000,
    
    // 405
    'methodNotAllowed' => 405000,
    
    // 500
    'otherError'      => 500001,
    'couldNotCreateToken' => 500002,
];
