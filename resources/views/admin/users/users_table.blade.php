@foreach($users as $user)
<tr id="user-{{ $user->id }}" data-user='{{ json_encode($user) }}'>
    <td class="align-middle">{{ $loop->iteration }}</td>
    <td class="align-middle fw-bold" style="color: #D4AF37;">{{ $user->name }}</td>
    <td class="align-middle">
        <div>{{ $user->email }}</div>
<small style="color: #b8b8b8; font-size: 0.85rem;">
    <i class="bi bi-telephone-fill" style="font-size: 0.75rem;"></i> {{ $user->phone_number ?? '-' }}
</small>  
  </td>
    <td class="align-middle">
        <span class="badge bg-secondary">{{ $user->role === 'admin' ? __('admin.admin_role') : __('admin.user') }}</span>
    </td>
    <td class="align-middle">
        @if($user->status === 'active')
            <span class="badge bg-soft-success text-success" style="border: 1px solid #28a745; padding: 5px 10px;">
                <i class="bi bi-patch-check-fill"></i> {{ __('admin.active') }}
            </span>
        @else
            <span class="badge bg-soft-danger text-danger" style="border: 1px solid #dc3545; padding: 5px 10px;">
                <i class="bi bi-x-octagon-fill"></i> {{ __('admin.inactive') }}
            </span>
        @endif
    </td>
   <td class="align-middle" style="font-size: 0.85rem; color: #d1c4a9;">
    <i class="bi bi-calendar3 me-1" style="font-size: 0.8rem;"></i>
    {{ $user->created_at ? $user->created_at->format('Y-m-d') : '-' }}
</td>
    <td class="align-middle" style="width: 1%; white-space: nowrap;">
        <div class="d-flex flex-nowrap gap-2 justify-content-center">
            <button class="btn btn-action btn-action-edit editUserBtn" title="{{ __('admin.edit') }}" style="color: #D4AF37; border: 1px solid #D4AF37;">
                <i class="bi bi-pencil-square"></i>
            </button>
            <button class="btn btn-action btn-action-delete deleteUserBtn" data-id="{{ $user->id }}" title="{{ __('admin.delete') }}" style="color: #dc3545; border: 1px solid #dc3545;">
                <i class="bi bi-trash3"></i>
            </button>
            <button class="btn btn-action btn-action-view viewUserBtn" title="{{ __('admin.view') }}" style="color: #0dcaf0; border: 1px solid #0dcaf0;">
                <i class="bi bi-eye"></i>
            </button>
        </div>
    </td>
</tr>
@endforeach