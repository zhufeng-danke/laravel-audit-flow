<?php

namespace WuTongWan\Flow\Http\Controllers;

use Illuminate\Http\Request;

class FlowController extends BaseController
{
    public function index()
    {
        $title = '审核流';

        $model_AuditFlow = new \WuTongWan\Flow\Models\AuditFlow();
        $list = $model_AuditFlow->setTable("audit_flows as f")->select("f.*","t.title as type_title","u.name")
                ->leftJoin('audit_bill_types as t','f.audit_bill_type_id','=','t.id')
                ->leftJoin('audit_associated_user_informations as u','f.creator_id','=','u.id')
                ->paginate(10);

        $model_AuditBillType = new \WuTongWan\Flow\Models\AuditBillType();
        $type_list = $model_AuditBillType->all();

        $model_AuditAssociatedUserInformation = new \WuTongWan\Flow\Models\AuditAssociatedUserInformation();
        $user_list = $model_AuditAssociatedUserInformation->getList();

        return view('flow::index', compact('title','list','type_list','user_list'));
    }

    public function getType()
    {
        $title = '审核流资源';

        $model_AuditBillType = new \WuTongWan\Flow\Models\AuditBillType();
        $list = $model_AuditBillType->setTable('audit_bill_types as t')->select("t.*","u.name")->leftJoin("audit_associated_user_informations as u",'u.id','=','t.creator_id')->paginate(10);

        $model_AuditAssociatedUserInformation = new \WuTongWan\Flow\Models\AuditAssociatedUserInformation();
        $user_list = $model_AuditAssociatedUserInformation->getList();

        return view('flow::type', compact('title','list','user_list'));
    }

    public function createFlow(Request $request)
    {
        $id = $request->input("id");

        if($request->isMethod('post')) {

            $audit_bill_type_id = $request->input("audit_bill_type_id");
            if($audit_bill_type_id <= 0) {
                echo json_encode(['status' => 0, 'message' => '请选择资源标识!']);
                exit;
            }

            $title = $request->input("title");
            if(empty($title))
            {
                echo json_encode(['status' => 0, 'message' => '请填审核流名称!']);
                exit;
            }

            $description = $request->input("description");

            $creator_id = $request->input("creator_id");
            if($creator_id <= 0) {
                echo json_encode(['status' => 0, 'message' => '请选择创建者!']);
                exit;
            }

            $status = $request->input("status");
            if(!isset($status)) {
                echo json_encode(['status' => 0, 'message' => '请选择状态!']);
                exit;
            }

            if($id > 0) {
                $model_AuditFlow = new \WuTongWan\Flow\Models\AuditFlow();
                $return = $model_AuditFlow->where("id","=",$id)->update(['audit_bill_type_id' => $audit_bill_type_id,'title' => $title, 'description' => $description, 'creator_id' => $creator_id, 'status' => $status]);
                if($return) {
                    echo json_encode(['status' => 1, 'message' => '更新成功']);
                    exit;
                }else{
                    echo json_encode(['status' => 0, 'message' => '更新失败']);
                    exit;
                }
            } else {
                $model_AuditFlow = new \WuTongWan\Flow\Models\AuditFlow();

                $count = $model_AuditFlow->where('title','=',$title)->count();
                if($count > 0) {
                    echo json_encode(['status' => 0, 'message' => '资源名称重复,请重新填写!']);
                    exit;
                }

                $model_AuditFlow->audit_bill_type_id = $audit_bill_type_id;
                $model_AuditFlow->title = $title;
                $model_AuditFlow->description = $description;
                $model_AuditFlow->creator_id = $creator_id;
                $model_AuditFlow->status = $status;
                $model_AuditFlow->save();
                $insert_id = $model_AuditFlow->id;

                if($insert_id > 0)
                {
                    echo json_encode(['status' => 1, 'message' => '创建成功']);
                    exit;
                }else{
                    echo json_encode(['status' => 0, 'message' => '创建失败']);
                    exit;
                }
            }
        }else {
            $model_AuditFlow = new \WuTongWan\Flow\Models\AuditFlow();
            $data = $model_AuditFlow->find($id);
            echo json_encode($data);
            exit;
        }
    }

    public function createType(Request $request)
    {
        $id = $request->input("id");

        if($request->isMethod('post')) {

            $title = $request->input("title");
            if(empty($title))
            {
                echo json_encode(['status' => 0, 'message' => '请填写资源名称']);
                exit;
            }

            $description = $request->input("description");

            $creator_id = $request->input("creator_id");

            if($id > 0) {
                $model_AuditBillType = new \WuTongWan\Flow\Models\AuditBillType();
                $return = $model_AuditBillType->where("id","=",$id)->update(['title' => $title, 'description' => $description, 'creator_id' => $creator_id]);
                if($return) {
                    echo json_encode(['status' => 1, 'message' => '更新成功']);
                    exit;
                }else{
                    echo json_encode(['status' => 0, 'message' => '更新失败']);
                    exit;
                }
            } else {
                $model_AuditBillType = new \WuTongWan\Flow\Models\AuditBillType();

                $count = $model_AuditBillType->where('title','=',$title)->count();
                if($count > 0) {
                    echo json_encode(['status' => 0, 'message' => '资源名称重复,请重新填写!']);
                    exit;
                }

                $model_AuditBillType->title = $title;
                $model_AuditBillType->description = $description;
                $model_AuditBillType->creator_id = $creator_id;
                $model_AuditBillType->save();
                $insert_id = $model_AuditBillType->id;

                if($insert_id > 0)
                {
                    echo json_encode(['status' => 1, 'message' => '创建成功']);
                    exit;
                }else{
                    echo json_encode(['status' => 0, 'message' => '创建失败']);
                    exit;
                }
            }
        }else {
            $model_AuditBillType = new \WuTongWan\Flow\Models\AuditBillType();
            $data = $model_AuditBillType->find($id);
            echo json_encode($data);
            exit;
        }
    }

    public function delFlow(Request $request)
    {
        $id = $request->input("id");

        $model_AuditNode = new \WuTongWan\Flow\Models\AuditNode();
        $count = $model_AuditNode->where("audit_flow_id","=",$id)->count();

        if($count > 0) {
            echo json_encode(['status' => 0, 'message' => '审核已设置节点,不允许删除!']);
            exit;
        }

        $model_AuditFlow = new \WuTongWan\Flow\Models\AuditFlow();
        $return = $model_AuditFlow->where("id","=",$id)->delete();
        if($return) {
            echo json_encode(['status' => 1, 'message' => '删除成功']);
            exit;
        }else{
            echo json_encode(['status' => 0, 'message' => '删除失败']);
            exit;
        }
    }


    public function delType(Request $request)
    {
        $id = $request->input("id");

        $model_AuditFlow = new \WuTongWan\Flow\Models\AuditFlow();
        $count = $model_AuditFlow->where("audit_bill_type_id","=",$id)->count();

        if($count > 0) {
            echo json_encode(['status' => 0, 'message' => '有工作流使用些资源,不允许删除!']);
            exit;
        }

        $model_AuditBillType = new \WuTongWan\Flow\Models\AuditBillType();
        $return = $model_AuditBillType->where("id","=",$id)->delete();
        if($return) {
            echo json_encode(['status' => 1, 'message' => '删除成功']);
            exit;
        }else{
            echo json_encode(['status' => 0, 'message' => '删除失败']);
            exit;
        }
    }

    public function getNode(Request $request)
    {
        $title = "设置节点";

        $flow_id = $request->input("flow_id");
        $model_AuditFlow = new \WuTongWan\Flow\Models\AuditFlow();
        $flow_info = $model_AuditFlow->find($flow_id);

        $model_AuditNode = new \WuTongWan\Flow\Models\AuditNode();
        $list = $model_AuditNode->select('*')->where('audit_flow_id','=',$flow_id)->orderBy('step','ASC')->get();

        $model_AuditUser = new \WuTongWan\Flow\Models\AuditUser();
        foreach ($list as $key => $val) {
            $list[$key]['users'] = $model_AuditUser->select("audit_users.*","audit_associated_user_informations.name","audit_associated_user_informations.email")
                                    ->leftJoin('audit_associated_user_informations', 'audit_users.audit_associated_user_information_id', '=', 'audit_associated_user_informations.id')
                                    ->where('audit_users.audit_flow_id','=',$flow_id)
                                    ->where("audit_users.audit_node_id",'=',$val['id'])
                                    ->orderBy('audit_users.order','ASC')
                                    ->get();
        }

        $model_AuditAssociatedUserInformation = new \WuTongWan\Flow\Models\AuditAssociatedUserInformation();
        $user_list = $model_AuditAssociatedUserInformation->getList();

        return view('flow::node', compact('title','flow_info','list','user_list'));
    }

    //创建节点
    public function createNode(Request $request)
    {
        $id = $request->input("id");

        if($request->isMethod('post')) {

            $parent_audit_node_id = $request->input("parent_audit_node_id");

            if($parent_audit_node_id > 0) {
                $model_AuditNode = new \WuTongWan\Flow\Models\AuditNode();
                $parent_node_info = $model_AuditNode->find($parent_audit_node_id);

                if(empty($parent_node_info)) {
                    echo json_encode(['status' => 0, 'message' => '父节点错误!']);
                    exit;
                }
                $step = $parent_node_info->step + 1;
            }else{
                $step = 1;
            }

            $audit_flow_id = $request->input("audit_flow_id");

            $title = $request->input("title");
            if(empty($title))
            {
                echo json_encode(['status' => 0, 'message' => '请填审核流名称!']);
                exit;
            }

            $description = $request->input("description");

            $audit_type = $request->input("audit_type");

            $is_end_flow = $request->input("is_end_flow");

            $creator_id = $request->input("creator_id");
            if($creator_id <= 0) {
                echo json_encode(['status' => 0, 'message' => '请选择创建者!']);
                exit;
            }

            if(!isset($audit_type)) {
                echo json_encode(['status' => 0, 'message' => '请选择审核类型!']);
                exit;
            }

            if(!isset($is_end_flow)) {
                echo json_encode(['status' => 0, 'message' => '请选择是否终点!']);
                exit;
            }

            if($id > 0) {
                $model_AuditNode = new \WuTongWan\Flow\Models\AuditNode();
                $return = $model_AuditNode->where("id","=",$id)->update(['parent_audit_node_id' => $parent_audit_node_id,'audit_flow_id' => $audit_flow_id,
                                            'title' => $title, 'description' => $description, 'step' => $step ,'creator_id' => $creator_id, 'audit_type' => $audit_type, 'is_end_flow' => $is_end_flow]);
                if($return) {
                    echo json_encode(['status' => 1, 'message' => '更新成功']);
                    exit;
                }else{
                    echo json_encode(['status' => 0, 'message' => '更新失败']);
                    exit;
                }
            } else {
                $model_AuditNode = new \WuTongWan\Flow\Models\AuditNode();

                $count = $model_AuditNode->where('title','=',$title)->count();
                if($count > 0) {
                    echo json_encode(['status' => 0, 'message' => '资源名称重复,请重新填写!']);
                    exit;
                }

                $model_AuditNode->parent_audit_node_id = $parent_audit_node_id;
                $model_AuditNode->audit_flow_id = $audit_flow_id;
                $model_AuditNode->title = $title;
                $model_AuditNode->description = $description;
                $model_AuditNode->step = $step;
                $model_AuditNode->audit_type = $audit_type;
                $model_AuditNode->is_end_flow = $is_end_flow;
                $model_AuditNode->creator_id = $creator_id;
                $model_AuditNode->save();
                $insert_id = $model_AuditNode->id;

                if($insert_id > 0)
                {
                    echo json_encode(['status' => 1, 'message' => '创建成功']);
                    exit;
                }else{
                    echo json_encode(['status' => 0, 'message' => '创建失败']);
                    exit;
                }
            }
        }else {
            $model_AuditNode = new \WuTongWan\Flow\Models\AuditNode();
            $data = $model_AuditNode->find($id);
            echo json_encode($data);
            exit;
        }
    }

    //创建审核人
    public function createUser(Request $request)
    {
        $id = $request->input("id");

        if($request->isMethod('post')) {

            $audit_flow_id = $request->input("audit_flow_id");

            $audit_node_id = $request->input("audit_node_id");

            $audit_associated_user_information_id = $request->input("audit_associated_user_information_id");
            if($audit_associated_user_information_id <= 0) {
                echo json_encode(['status' => 0, 'message' => '请选择审核人!']);
                exit;
            }

            $creator_id = $request->input("creator_id");
            if($creator_id <= 0) {
                echo json_encode(['status' => 0, 'message' => '请选择创建者!']);
                exit;
            }

            $order = $request->input("order");

            if($id > 0) {
                $model_AuditUser = new \WuTongWan\Flow\Models\AuditUser();
                $return = $model_AuditUser->where("id","=",$id)->update(['audit_associated_user_information_id' => $audit_associated_user_information_id,'order' => $order, 'creator_id' => $creator_id]);
                if($return) {
                    echo json_encode(['status' => 1, 'message' => '更新成功']);
                    exit;
                }else{
                    echo json_encode(['status' => 0, 'message' => '更新失败']);
                    exit;
                }
            } else {
                $model_AuditUser = new \WuTongWan\Flow\Models\AuditUser();

                $model_AuditUser->audit_flow_id = $audit_flow_id;
                $model_AuditUser->audit_node_id = $audit_node_id;
                $model_AuditUser->audit_associated_user_information_id = $audit_associated_user_information_id;
                $model_AuditUser->order = $order;
                $model_AuditUser->creator_id = $creator_id;
                $model_AuditUser->save();
                $insert_id = $model_AuditUser->id;

                if($insert_id > 0)
                {
                    echo json_encode(['status' => 1, 'message' => '创建成功']);
                    exit;
                }else{
                    echo json_encode(['status' => 0, 'message' => '创建失败']);
                    exit;
                }
            }
        }else {
            $model_AuditUser = new \WuTongWan\Flow\Models\AuditUser();
            $data = $model_AuditUser->find($id);
            echo json_encode($data);
            exit;
        }
    }

    public function delUser(Request $request)
    {
        $id = $request->input("id");

        $model_AuditUser = new \WuTongWan\Flow\Models\AuditUser();
        $return = $model_AuditUser->where("id","=",$id)->delete();
        if($return) {
            echo json_encode(['status' => 1, 'message' => '删除成功']);
            exit;
        }else{
            echo json_encode(['status' => 0, 'message' => '删除失败']);
            exit;
        }
    }

    public function getBill()
    {
        $title = "审核单据列表";

        $model_AuditBillAndFlowRelations = new \WuTongWan\Flow\Models\AuditBillAndFlowRelations();
        $list = $model_AuditBillAndFlowRelations->setTable('audit_bill_and_flow_relations as r')
            ->select('r.*','f.title as flow_title','t.title as type_title')
            ->leftJoin('audit_flows as f', 'f.id', '=', 'r.audit_flow_id')
            ->leftJoin('audit_bill_types as t', 't.id', '=', 'r.audit_bill_type_id')
            ->paginate(10);

        return view('flow::bill', compact('title','list'));
    }

    public function getRecords(Request $request)
    {
        $title = "审核操作记录";

        $bill_id = $request->input("bill_id");

        $model_AuditRecord = new \WuTongWan\Flow\Models\AuditRecord();
        $list = $model_AuditRecord->setTable("audit_records as r")
                                ->select('r.*','f.title as flow_title','n.title as node_title','u.name','u.email')
                                ->leftJoin('audit_flows as f', 'f.id', '=', 'r.audit_flow_id')
                                ->leftJoin('audit_nodes as n', 'n.id', '=', 'r.audit_node_id')
                                ->leftJoin('audit_associated_user_informations as u', 'u.id', '=', 'r.audit_user_id')
                                ->where('bill_id','=',$bill_id)
                                ->get();

        return view('flow::records', compact('title','list'));
    }

}

