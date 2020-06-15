<table class="table table-bordered table-striped table-vcenter">
    <!--
        大有  表单头部
        表单头部的th，添加class="table_arr"，则增加右侧排序箭头
        -->
    <thead>
        <tr>
            <th class="table_arr">ID</th>
            <th class="table_arr">来源</th>
            <th class="table_arr">子账号名称</th>
            <th class="table_arr">公司名称</th>
            <th class="table_arr">电话</th>
            <th class="table_arr">创建时间</th>
            <th class="table_arr">申诉提交时间</th>
            <th class="table_arr">申诉原因</th>
            <th style="width:80px">状态</th>
            <th style="width:150px">操作</th>
            <th>拒绝理由</th>
        </tr>
    </thead>

    <tbody>
        @foreach($data as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <!--id序号-->
            <td>{{ $item->info->source }}</td>
            <!--来源-->
            <td>{{ $item->childAccount->name }}</td>
            <!--子账号名称-->
            <td>{{ $item->info->getName() }}</td>
            <!--公司名称-->
            <td>{{ $item->info->phone_number }}</td>
            <!--电话-->
            <td>{{ $item->info->created_at }}</td>
            <td>{{ $item->created_at }}</td>
            <!--申请提交时间-->
            <td>{{ $item->apply_reason }}</td>
            <!--申请原因：子账号的申请原因-->
            <td>{!! $item->getStatus() !!}</td>
            <!--状态，有三种：申请中（text-blue）, 通过（text-green）,未通过（text-red）。-->
            <td>
                <!--操作，只有在[申请中]状态时，才会有操作按钮-->
                @if ($item->status == \App\Entities\Apply::STATUS_PENDING)
                <div class="btn-group">
                    <a href="{{ route("info.apply.pass", ["id" => $item->id]) }}" class="btn btn-sm btn-success">通过</a>
                    <!--点击 [通过] 直接通过，不会有弹框再次确认-->
                    <button data-href="{{ route("info.apply.refuse", ["id" => $item->id]) }}" class="btn btn-sm btn-app-red table_btn_reason">拒绝</button>
                    <!--有弹框，填写拒绝理由-->
                </div>
                @endif
            </td>
            <td>{{ $item->status == \App\Entities\Apply::STATUS_REFUSE ? $item->refuse_reason : "" }}</td>
            <!--拒绝理由，只有拒绝后，才会有拒绝内容，  在状态为申请中、通过时，此处没有内容-->
        </tr>
        @endforeach
    </tbody>
</table>