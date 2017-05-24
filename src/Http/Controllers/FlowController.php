<?php

namespace WuTongWan\Flow\Http\Controllers;

use Illuminate\Http\Request;

class FlowController extends BaseController
{
    public function index()
    {
        $title = '审核流';

        $model_AuditFlow = new \WuTongWan\Flow\Models\AuditFlow();
        $list = $model_AuditFlow->select("*")->get();

        return view('flow::index', compact('title','list'));
    }

    public function getType()
    {
        $title = '审核流资源';

        $model_AuditBillType = new \WuTongWan\Flow\Models\AuditBillType();
        $list = $model_AuditBillType->setTable('audit_bill_types as t')->select("t.*","u.name")->leftJoin("audit_associated_user_informations as u",'u.id','=','t.creator_id')->paginate(2);

        $model_AuditAssociatedUserInformation = new \WuTongWan\Flow\Models\AuditAssociatedUserInformation();
        $user_list = $model_AuditAssociatedUserInformation->getList();

        return view('flow::type', compact('title','list','user_list'));
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


}

