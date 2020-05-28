<table class="table table-bordered table-striped table-vcenter">
    <!-- 10.15  大有 新加最后一个样式(dataTable-info-info-list )(为了表格数据js)-->
    <!--
        大有  表单头部
        表单头部的th，添加class="table_arr"，则增加右侧排序箭头
        -->
    <thead>
        <tr>
            <th class="table_arr">ID</th>
            <th class="table_arr">类型</th>
            <th class="table_arr">来源</th>
            <th class="table_arr">公司名称或老板姓名</th>
            <th class="table_arr">电话</th>
            <th class="table_arr">所属子账号</th>
            <th class="table_arr">客户IP</th>
            <th class="table_arr">提交时间</th>
            <th style="width:180px">操作</th>
        </tr>
    </thead>

    <tbody>
        @foreach($data as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <!--id序号-->
            <td>{{ $item->getType() }}</td>
            <!--类型，有两种：核名，起名-->
            <td>{{ $item->source }}</td>
            <!--是在落地页获取到的-->
            <td>{{ $item->type == \App\Entities\Info::TYPE_CHECK ? $item->company_name : $item->boss_name }}</td>
            <!--公司名称-->
            <td>{{ $item->phone_number }}</td>
            <!--电话-->
            <td>{{ $item->child_account ? $item->child_account->name : '未指定' }}</td>
            <!--所属子账号-->
            <td>{{ $item->client_ip }}</td>
            <td>{{ $item->created_at }}</td>
            <!--提交时间-->
            <td>
                <!--操作-->
                <div class="btn-group">
                    <a href="{{ route('info.change', ['id' => $item->id]) }}" class="btn btn-sm btn-success">更改分配</a>
                    <!--跳转新页面-->
                    <button data-href="{{ route('info.delete', ['id' => $item->id]) }}" class="btn btn-sm btn-app-red table_btn_del">删除</button>
                    <!--弹框-->
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>