
# Laravel-Audit-Flow

## 安装

    composer require --prefer-source zhufeng-danke/laravel-audit-flow

项目还处于开发者，建议从开发包，直接安装。

## 业务逻辑




## 访问方法

### 创建流

跳转到审核流扩展包的流创建页面；

路由名称：

    flow-index 

必须参数：

    user_id 当前用户ID 

### 单据绑定审核流相关接口

#### 获取可用审核流
访问接口：

    Flow::queryFlow($bill_id)

输入参数：
    
    $bill_id 单据ID，后续查询审核流相关信息的唯一标识

返回数据（示例）：

    array:3 [▼
      "relation_id" => 0                           ＃ 绑定记录ID，未绑定则为0
      "status" => "2"                              ＃ 状态：0，无可用流；1，已绑定；2，未绑定；
      "data" => array:3 [▼
        "msg" => ""                                ＃ 对状态的说明
        "url" => "create-bill-flow-relations"
        "flows" => array:2 [▼                      ＃ 可用流信息的数组
          0 => {#235 ▼
            +"flow_id": 1
            +"title": "收房合同审核流－20170519"
          }
          1 => {#237 ▼
            +"flow_id": 2
            +"title": "收房合同审核流－20170525"
          }
        ]
      ]
    ]


#### 执行绑定
访问接口：
    
    Flow::billBindFlow($bill_id, $flow_id, $user_id);

输入参数：
    
    $bill_id 单据ID
    $flow_id 选择的审核流ID
    $user_id 业务中用户ID

输出：

    true 绑定成功；false 绑定失败。

#### 解除绑定
 
访问接口：

    Flow::deleteBillRelation($relation_id, $bill_id, $flow_id, $user_id)

输入参数：

    $relation_id 绑定记录ID
    $bill_id     单据ID
    $flow_id     解绑流ID
    $user_id     业务中用户ID

返回数据：

    true 解绑成功；false 解绑失败
    
解绑失败可能性：已有审核记录或非创建人无权解绑    

### 查看审核记录

跳转到扩展包的审核记录页面；

路由名称：

    flow-records-index

必须参数：

    bill_id 单据ID
    user_id 当前用户ID 


### 查看审核人待审核信息接口

访问接口：
    
    Flow::queryCheck($user_id，$bill_id = '')
    
输入参数：
    
    $user_id 必须，业务中用户ID
    $bill_id 可选，单据ID；查询指定单据的审核信息；
    
返回数据（示例）：

    array:3 [▼
      0 => {#233 ▼
        +"bill_id": "DKGYBJ1120300503003"         ＃ 单据ID
        +"audit_flow_id": 1                       ＃ 审核流ID
        +"flow": "收房合同审核流－20170519"         ＃ 审核流名称
        +"audit_node_id": 1                       ＃ 节点ID
        +"node": "管家查看是否可租"                 ＃ 节点名称
        +"origin_user_id": 1                      ＃ 业务中用户ID
        +"audit_user_id": 6                       ＃ 审核节点的审核人记录ID
        +"audit_records": array:1 [▼              ＃ 当前节点审核人审核记录数字
          0 => {#241 ▼                            ＃ 审核人审核记录
            +"audit_user_id": 6                   ＃ 节点审核人记录ID
            +"audit_record_id": 1                 ＃ 审核记录ID
            +"action": 1                          ＃ 审核结果 1，通过；2，终止；3，撤销；
            +"action_description": "通过"          ＃ 审核结果描述
            +"comment": ""                         ＃ 审核人评价
          }
        ]
        +"current_action": 1                       ＃ 节点最新审核结果
        +"current_comment": ""                     ＃ 节点最新审核评论
      }
      1 => {#239 ▼
        +"bill_id": "DKGYBJ1120300503003"
        +"audit_flow_id": 1
        +"flow": "收房合同审核流－20170519"
        +"audit_node_id": 2
        +"node": "检修"
        +"origin_user_id": 1
        +"audit_user_id": 7
        +"audit_records": ""
        +"current_action": ""
        +"current_comment": ""
      }
    ]

### 存储审核结果

访问接口：

    storeCheckResult($audit_user_id, $bill_id, $current_user_id, $action, $comment = '')

输入参数

必须参数：

    $audit_user_id     节点审核人记录ID
    $bill_id           单据ID
    $current_user_id   业务中用户ID
    $action            审核结果 1，通过；2，终止；3，撤销；
    
可选参数：
    
    $comment           审核评论 
    
返回数据：

    true 成功；false 失败；

