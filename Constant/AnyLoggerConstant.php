<?php

namespace Andrewlynx\Bundle\Constant;

class AnyLoggerConstant
{
    public const FILENAME = 'filename';
    public const FILE_PREFIX = 'log-';
    public const FILE_EXTENSION = '.log';

    public const NAME_DATE = 'date';
    public const NAME_EVENT = 'event';
    public const NAME_DATE_EVENT = 'date-event';

    public const PARSE_JSON_SIZE_LIMIT = 'parse_json_size_limit';
    public const DEFAULT_PARSE_JSON_SIZE_LIMIT = 20*1024; // parse files with size only < 20MB

    public const APP_NAME = 'andrewlynx.any_logger';

    public const FIELD_DATE = 'date';
    public const FIELD_EVENT = 'event';
    public const FIELD_DATA = 'data';
}