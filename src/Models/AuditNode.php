<?php

namespace WuTongWan\Flow\Models;

class AuditNode extends BaseModel
{
    //
    public static $audit_type = [
        1 => '全通过',
        2 => '至少一人通过',
    ];
}
