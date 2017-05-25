<?php

namespace WuTongWan\Flow\Models;

class AuditRecord extends BaseModel
{
    /**
     * 存储审核结果方法
     * @param $bill_id //单据ID
     * @param $audit_user_id //audit_users表ID
     * @param $action //审核人行为，默认0，未处理；1，通过；2，退回上步；3，否决，终止审核流；4，撤销审核人行为
     * @param $comment //审核人评论
     */
    public function setRecordAction($bill_id,$audit_user_id,$action,$comment)
    {
        //先确认有没有这一步审核人
        $model_AuditUser = new \WuTongWan\Flow\Models\AuditUser();
        $audit_users_info = $model_AuditUser->setTable('audit_users as u')
                            ->leftJoin('audit_flows as f', 'u.audit_flow_id', '=', 'f.id')
                            ->where('u.id','=',$audit_user_id)
                            ->get();

        if(empty($audit_users_info)) {
            return false;
        }

        //这一步审核的是哪个节点
        $model_AuditNode = new \WuTongWan\Flow\Models\AuditNode();
        $node_info = $model_AuditNode->where('audit_flow_id','=',$audit_users_info->audit_flow_id)->where('id','=',$audit_users_info->audit_node_id)->get();
        if(empty($node_info)) {
            return false;
        }

        //查询单据ID现在最后一步审核到哪个步骤
        $audit_records_last = self::setTable("audit_records as r")
            ->select('r.*','f.title as flow_title','n.title as node_title','u.name','u.email')
            ->leftJoin('audit_flows as f', 'f.id', '=', 'r.audit_flow_id')
            ->leftJoin('audit_nodes as n', 'n.id', '=', 'r.audit_node_id')
            ->leftJoin('audit_associated_user_informations as u', 'u.id', '=', 'r.audit_user_id')
            ->where('r.bill_id','=',$bill_id)
            ->orderBy('r.id', 'desc')
            ->get();

        //查询单据ID下一步应该审核哪个节点
        $next_node_info = $model_AuditNode->where('audit_flow_id','=',$audit_records_last->audit_flow_id)->where('id','!=',$audit_records_last->audit_node_id)->get();

        if(empty($next_node_info)) {
            return false;
        }

        if($node_info->id != $next_node_info->id) {
            return false;
        }

        //audit_bill_and_flow_relations表审核记录
        $model_AuditBillAndFlowRelations = new \WuTongWan\Flow\Models\AuditBillAndFlowRelations();
        $bill_flow_info = $model_AuditBillAndFlowRelations->where('bill_id','=',$bill_id)->get();

        if(empty($bill_flow_info)) {
            $model_AuditBillAndFlowRelations->bill_id = $bill_id;
            $model_AuditBillAndFlowRelations->audit_flow_id = $audit_users_info->audit_flow_id;
            $model_AuditBillAndFlowRelations->audit_bill_type_id = $audit_users_info->audit_bill_type_id;
            $model_AuditBillAndFlowRelations->creator_id = $audit_users_info->audit_associated_user_information_id;
            $model_AuditBillAndFlowRelations->save();

            $bill_flow_id = $model_AuditBillAndFlowRelations->id;
        }else{
            $bill_flow_id = $bill_flow_info->id;
        }

        if(empty($bill_flow_id) || $bill_flow_id <= 0) {
            return false;
        }

        $model_AuditRecord = new \WuTongWan\Flow\Models\AuditRecord();
        $model_AuditRecord->bill_id = $bill_id;
        $model_AuditRecord->audit_flow_id = $audit_users_info->audit_flow_id;
        $model_AuditRecord->audit_node_id = $audit_users_info->audit_node_id;
        $model_AuditRecord->audit_user_id = $audit_users_info->audit_associated_user_information_id;
        $model_AuditRecord->action = $action;
        $model_AuditRecord->comment = $comment;
        $model_AuditRecord->save();

        if($model_AuditRecord->id > 0) {
            return true;
        }else{
            return false;
        }
    }
}
