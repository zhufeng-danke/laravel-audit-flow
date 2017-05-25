<?php

namespace WuTongWan\Flow\Models;

class AuditBillAndFlowRelations extends BaseModel
{
    //获取审核流详情方法
    public function getFlowInfo($bill_id)
    {
        if(empty($bill_id)) {
            return [];
        }

        $flow_data = self::setTable('audit_bill_and_flow_relations as r')
            ->select('r.*','f.title as flow_title','t.title as type_title')
            ->leftJoin('audit_flows as f', 'f.id', '=', 'r.audit_flow_id')
            ->leftJoin('audit_bill_types as t', 't.id', '=', 'r.audit_bill_type_id')
            ->where('r.bill_id','=',$bill_id)
            ->get();

        if(!empty($flow_data)) {
            $model_AuditRecord = new \WuTongWan\Flow\Models\AuditRecord();
            $flow_data->records = $model_AuditRecord->setTable("audit_records as r")
                ->select('r.*','f.title as flow_title','n.title as node_title','u.name','u.email')
                ->leftJoin('audit_flows as f', 'f.id', '=', 'r.audit_flow_id')
                ->leftJoin('audit_nodes as n', 'n.id', '=', 'r.audit_node_id')
                ->leftJoin('audit_associated_user_informations as u', 'u.id', '=', 'r.audit_user_id')
                ->where('r.bill_id','=',$bill_id)
                ->get();
        }

        return $flow_data;
    }
}
