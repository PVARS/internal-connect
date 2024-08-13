<?php

namespace App\Utils;

class Constants
{
    // User
    public const PER_PAGE_LIST_USERS = 100;

    public const FIRST_NAME_MAX_LENGTH = 100;

    public const LAST_NAME_MAX_LENGTH = 100;

    public const EMAIL_MAX_LENGTH = 255;

    public const USERNAME_MAX_LENGTH = 50;

    public const PHONE_MAX_LENGTH = 50;

    public const PROVINCE_MAX_LENGTH = 100;

    public const DISTRICT_MAX_LENGTH = 100;

    public const WARD_MAX_LENGTH = 100;

    public const ADDRESS_MAX_LENGTH = 255;

    public const PASSWORD_MAX_LENGTH = 255;

    public const PASSWORD_MIN_LENGTH = 8;

    public const VERIFY_USER_TOKEN_MAX_LENGTH = 255;

    public const SIZE_AVATAR_PROFILE = 500; // 500 KB

    // Format date
    public const DATE_FORMAT_ISO = 'Y-m-d';

    // Util class
    public const string REGEX_VALID_TLD = '/\.[a-z]{2,}$/i';

    // Disk S3
    public const string USER_AVATARS = 'user-avatars';
}
