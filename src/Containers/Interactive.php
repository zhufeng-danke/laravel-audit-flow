<?php

namespace WuTongWan\Flow\Containers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use WuTongWan\Flow\Models\AuditBillAndFlowRelations;

class Interactive
{

    const FLOW_RECORD_ROUTE_NAME = 'flow-records-index';
    const FLOW_CREATE_ROUTE_NAME = 'flow-index';
    const FLOW_BIND_BILL_ID_ROUTE_NAME = 'create-bill-flow-relations';

    /**
     * 查询单据流信息
     * @param        $bill_id
     * @param string $user_id
     * @return array
     */
    public function queryFlow($bill_id, $user_id = '')
    {
        //单据已绑定资源
        $relation = AuditBillAndFlowRelations::where('bill_id', $bill_id)->first();
        if ($relation) {
            return $this->updateResult('1', '已生成流',
                route(self::FLOW_RECORD_ROUTE_NAME, ['bill_id' => $relation->bill_id, 'user_id' => $user_id]));
        }

        //单据未绑定资源
        $flows = DB::table('audit_flows')->select('id as flow_id', 'title')->where('status', 1)->get();
        if (!count($flows)) {
            return $this->updateResult('0', '无可用流，请先创建流。',
                route(self::FLOW_CREATE_ROUTE_NAME, ['user_id' => $user_id]));
        }

        return $this->updateResult('2', '未绑定流；从返回的流中选择，进行绑定。', self::FLOW_BIND_BILL_ID_ROUTE_NAME, $flows->toArray());
    }

    public function updateResult($status = 0, $msg = '异常', $url = '', $resource = '')
    {
        return [
            'status' => $status,
            'data' => [
                'msg' => $msg,
                'url' => $url,
                'flows' => $resource
            ]
        ];
    }

    /**
     * 查询审核信息
     * @param string $origin_user_id 业务中用户ID，可选
     * @param string $bill_id 业务中单据ID，可选
     * @return bool|mixed
     */
    public function queryCheck($origin_user_id = '', $bill_id = '')
    {
        // 查询审核信息
        $audit_users = $this->queryAuditUserByOriginUserIdOrBillId($origin_user_id, $bill_id);

        // 查询审核记录
        $audit_users_with_records = $this->queryAuditRecords($audit_users);

        return $audit_users_with_records;
    }

    /**
     * 存储审核结果
     * @param        $audit_user_id
     * @param        $bill_id
     * @param        $current_user_id
     * @param string $action
     * @param string $comment
     * @return bool
     */
    public function storeCheckResult($audit_user_id, $bill_id, $current_user_id, $action = '', $comment = '')
    {
        // 获取审核信息
        $audit_user = $this->queryAduitUserById($audit_user_id);

        // 核对信息
        if (count($audit_user) != 1 || $audit_user[0]->origin_user_id != $current_user_id || !in_array($action,
                [1, 2, 3, 4])
        ) {
            return false;
        }

        // 存储
        do {
            $id = DB::table('audit_records')->insertGetId([
                'bill_id' => $bill_id,
                'audit_flow_id' => $audit_user[0]->audit_flow_id,
                'audit_node_id' => $audit_user[0]->audit_node_id,
                'audit_user_id' => $audit_user_id,
                'action' => $action,
                'comment' => $comment,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        } while (!$id);

        return true;
    }

    /**
     * 根据audit_user_id，获取审核信息
     * @param $audit_user_id
     * @return mixed
     */
    public function queryAduitUserById($audit_user_id)
    {
        $sql = "
SELECT
au.audit_flow_id,
au.audit_node_id,
aaui.origin_user_id,
au.id as 'audit_user_id'
FROM audit_users au
INNER JOIN audit_associated_user_informations aaui ON aaui.id = au.audit_associated_user_information_id
WHERE au.id = ?        
        ";

        return DB::select($sql, [$audit_user_id]);
    }

    /**
     * 查询审核记录
     * @param $audit_users
     * @return mixed
     */
    public function queryAuditRecords($audit_users)
    {
        if (count($audit_users)) {
            foreach ($audit_users as $key => $audit_user) {
                $audit_user_id = $audit_user->audit_user_id;
                if (!is_int($audit_user_id) || $audit_user_id <= 0) {
                    continue;
                }

                $audit_records = $this->queryAuditRecordById($audit_user_id);
                $audit_records_len = count($audit_records);
                if ($audit_records_len) {
                    $audit_users[$key]->current_action = $audit_records[$audit_records_len - 1]->action;
                    $audit_users[$key]->current_comment = $audit_records[$audit_records_len - 1]->comment;
                    $audit_users[$key]->audit_records = $audit_records;
                }
            }
        }

        return $audit_users;
    }

    /**
     * 根据audit_user_id，获取审核记录
     * @param $id
     * @return mixed
     */
    public function queryAuditRecordById($id)
    {
        $sql = "
SELECT
ar.audit_user_id,
ar.id as 'audit_record_id',
if( ar.action != '', ar.action, 0) as action,
case ar.action 
	when 1 then '通过' 
	when 2 then '退回上步' 
	when 3 then '终止审核流'
	when 4 then '撤销审核人行为'
else 
	'未处理' 
end as 'action_description',
ar.`comment`
FROM audit_users au
INNER JOIN audit_records ar ON ar.audit_user_id = au.id
WHERE au.id = ?        
        ";

        return DB::select($sql, [$id]);
    }

    /**
     * 查询审核相关信息
     * @param $origin_user_id
     * @param $bill_id
     * @return bool|mixed
     */
    public function queryAuditUserByOriginUserIdOrBillId($origin_user_id, $bill_id)
    {
        $audit_users = [];
        if ($origin_user_id && $bill_id) {
            $audit_users = $this->queryAuditUserByOriginUserIdAndBillId($origin_user_id, $bill_id);
        }

        if ($origin_user_id && !$bill_id) {
            $audit_users = $this->queryAuditUserByOriginUserId($origin_user_id);
        }

        if (!$origin_user_id && $bill_id) {
            $audit_users = $this->queryAuditUserByBillId($bill_id);
        }

        return $audit_users;
    }

    /**
     * 根据业务中用户ID，获取审核相关信息
     * @param $user_id
     * @return mixed
     */
    public function queryAuditUserByOriginUserId($user_id)
    {
        $sql = "
SELECT
abafr.bill_id,

af.id as 'audit_flow_id',
af.title as 'flow',

an.id as 'audit_node_id',
an.title as 'node',

aaui.origin_user_id,
au.id as 'audit_user_id',
'' as 'audit_records',
'' as 'current_action',
'' as 'current_comment'
FROM audit_associated_user_informations aaui
INNER JOIN audit_users au ON au.audit_associated_user_information_id = aaui.id
INNER JOIN audit_nodes an ON an.id = au.audit_node_id
INNER JOIN audit_flows af ON af.id = an.audit_flow_id
INNER JOIN audit_bill_and_flow_relations abafr ON abafr.audit_flow_id = af.id
WHERE aaui.origin_user_id = ?
ORDER BY af.id ASC, an.parent_audit_node_id ASC            
            ";

        return DB::select($sql, [$user_id]);
    }

    /**
     * 根据业务单据ID，获取审核相关信息
     * @param $bill_id
     * @return mixed
     */
    public function queryAuditUserByBillId($bill_id)
    {
        $sql = "
SELECT
abafr.bill_id,
af.id as 'audit_flow_id',
af.title as 'flow',
an.id as 'audit_node_id',
an.title as 'node',
aaui.origin_user_id,
au.id as 'audit_user_id',
'' as 'audit_records',
'' as 'current_action',
'' as 'current_comment'
FROM audit_associated_user_informations aaui
INNER JOIN audit_users au ON au.audit_associated_user_information_id = aaui.id
INNER JOIN audit_nodes an ON an.id = au.audit_node_id
INNER JOIN audit_flows af ON af.id = an.audit_flow_id
INNER JOIN audit_bill_and_flow_relations abafr ON abafr.audit_flow_id = af.id
WHERE abafr.bill_id = ?
ORDER BY af.id ASC, an.parent_audit_node_id ASC            
            ";

        return DB::select($sql, [$bill_id]);
    }

    /**
     * 根据业务中用户ID、业务单据ID，获取审核相关信息
     * @param $origin_user_id
     * @param $bill_id
     * @return mixed
     */
    public function queryAuditUserByOriginUserIdAndBillId($origin_user_id, $bill_id)
    {
        $sql = "
SELECT
abafr.bill_id,
af.id as 'audit_flow_id',
af.title as 'flow',
an.id as 'audit_node_id',
an.title as 'node',
aaui.origin_user_id,
au.id as 'audit_user_id',
'' as 'audit_records',
'' as 'current_action',
'' as 'current_comment'
FROM audit_associated_user_informations aaui
INNER JOIN audit_users au ON au.audit_associated_user_information_id = aaui.id
INNER JOIN audit_nodes an ON an.id = au.audit_node_id
INNER JOIN audit_flows af ON af.id = an.audit_flow_id
INNER JOIN audit_bill_and_flow_relations abafr ON abafr.audit_flow_id = af.id
WHERE aaui.origin_user_id = ? and abafr.bill_id = ?
ORDER BY af.id ASC, an.parent_audit_node_id ASC            
            ";

        return DB::select($sql, [$origin_user_id, $bill_id]);
    }

}
