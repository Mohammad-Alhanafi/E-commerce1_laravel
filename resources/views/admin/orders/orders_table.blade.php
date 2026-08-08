<div class="table-responsive custom-table-container">
    <table class="table mb-0 align-middle text-center" id="ordersTable">
        <thead>
            <tr>
                <th style="width: 70px;">#</th>
                <th class="text-start" style="width: 200px;">{{ __('admin.recipient_info') }}</th>
                <th style="width: 180px;">{{ __('admin.requested_products') }}</th> 
                <th style="width: 100px;">{{ __('admin.shipping_method') }}</th>
                <th class="text-start" style="width: 150px;">{{ __('admin.address_city') }}</th>
                <th style="width: 90px;">{{ __('admin.total_amount') }}</th>
                <th style="width: 100px;">{{ __('admin.status') }}</th>
                <th style="width: 150px;">{{ __('admin.notes') }}</th> 
                <th style="width: 110px;">{{ __('admin.date') }}</th>
                <th style="width: 100px;">{{ __('admin.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td class="text-gold fw-bold">{{ $order->id }}</td>
                
                <td class="text-start">
                    <div class="d-flex flex-column" style="line-height: 1.3;">
                        @php
                            $userName = $order->user->name ?? '';
                            $customerName = $order->customer_name ?? '';
                            $isSamePerson = (trim($userName) == trim($customerName)) || empty($customerName);
                        @endphp

                        @if($isSamePerson)
                            <span class="fw-bold small" style="color: var(--text-color);">
                                <i class="bi bi-person-fill text-gold me-1"></i>
                                {{ $userName ?: $customerName ?: __('admin.no_name') }}
                            </span>
                        @else
                            <span class="fw-bold small" style="color: var(--text-color);" title="{{ __('admin.recipient') }}">
                                <i class="bi bi-person-check-fill text-info me-1"></i>
                                {{ $customerName }}
                            </span>
                            <small style="font-size: 0.7rem; color: var(--primary-color); opacity: 0.8;" title="{{ __('admin.customer') }}">
                                <i class="bi bi-person-circle me-1"></i> {{ __('admin.by') }}: {{ $userName }}
                            </small>
                        @endif

                        <small class="text-success mt-1" style="font-size: 0.75rem; font-weight: 500;">
                            <i class="bi bi-whatsapp me-1"></i>
                            {{ $order->customer_phone ?: ($order->user->phone ?? '---') }}
                        </small>
                    </div>
                </td>

                <td class="text-start" style="min-width: 180px;">
                    <div class="d-flex flex-wrap gap-1 py-2"> 
                        @forelse($order->products as $product)
                            <div class="badge p-2 w-100" 
                                 style="background-color: color-mix(in srgb, var(--primary-color) 12%, var(--card-bg)); border: 1px solid var(--border-color); color: var(--primary-color); font-size: 0.7rem; white-space: normal; text-align: start; line-height: 1.4; display: block;">
                                
                                <strong style="color: var(--primary-color);">{{ $product->name }}</strong>
                                <span class="ms-1" style="color: var(--text-muted);">x{{ $product->pivot->quantity }}</span>

                                @if($product->pivot->variant_id)
                                    @php 
                                        $variant = isset($variantsMap) ? ($variantsMap[$product->pivot->variant_id] ?? null) : null; 
                                    @endphp
                                    
                                    @if($variant)
                                        <div style="color: var(--text-color); font-size: 0.65rem; margin-top: 4px; padding-top: 4px; border-top: 1px dotted color-mix(in srgb, var(--primary-color) 40%, transparent);">
                                            <i class="bi bi-info-circle me-1"></i>
                                            {{ $variant->size ?? '' }} {{ $variant->color ?? '' }}
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @empty
                            <div class="d-flex align-items-center opacity-75">
                                <i class="bi bi-exclamation-circle me-1" style="color: var(--danger-color); font-size: 0.8rem;"></i>
                                <small style="color: var(--danger-color); font-size: 0.7rem; font-weight: 500;">{{ __('admin.no_products') }}</small>
                            </div>
                        @endforelse
                    </div>
                </td>

                <td>
                    @if(($order->shipping_method ?? 'delivery') == 'delivery')
                        <i class="bi bi-truck text-info" title="{{ __('admin.delivery') }}"></i>
                    @else
                        <i class="bi bi-shop text-warning" title="{{ __('admin.pickup') }}"></i>
                    @endif
                </td>

                <td class="text-start">
                    <div class="d-flex flex-column" style="line-height: 1.4;">
                        <span class="fw-bold small" style="color: var(--text-color);">
                            {{ $order->city ?: __('admin.not_specified') }}
                        </span>
                        
                        <div class="d-flex align-items-center mt-1">
                            <i class="bi bi-geo-alt-fill text-gold me-1" style="font-size: 0.8rem;"></i>
                            <small class="text-gold opacity-75" style="font-size: 0.7rem; white-space: normal;">
                                {{ Str::limit($order->address, 25) ?: __('admin.no_address') }}
                            </small>
                        </div>
                    </div>
                </td>
                
                <td class="text-success fw-bold">${{ number_format($order->total_price, 2) }}</td>

                <td>
                    @php
                        $statusKey = strtolower(trim($order->status));
                        $statusLabel = __('admin.' . $statusKey);
                        if ($statusLabel === 'admin.' . $statusKey) {
                            $statusLabel = $order->status;
                        }
                        $statusClass = 'status-' . ($statusKey === 'canceled' ? 'cancelled' : $statusKey);
                    @endphp
                    <span class="status-badge {{ $statusClass }}" style="font-size: 0.7rem;">
                        {{ $statusLabel }}
                    </span>
                </td>

                <td>
                    <small class="opacity-75" style="font-size: 0.7rem; color: var(--text-muted);">
                        {{ $order->notes ?: '---' }}
                    </small>
                </td>

                <td class="text-gold" style="font-size: 0.8rem;">
                    {{ $order->created_at ? $order->created_at->format('Y-m-d') : '---' }}
                </td>

                <td>
                    <div class="d-flex justify-content-center gap-1">
                        <button class="btn btn-sm btn-outline-gold editOrderBtn" data-id="{{ $order->id }}" title="{{ __('admin.edit') }}"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-outline-danger deleteOrderBtn" data-id="{{ $order->id }}" title="{{ __('admin.delete') }}"><i class="bi bi-trash"></i></button>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="10" class="py-4 text-muted">{{ __('admin.no_data') }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- الـ Pagination مدمج هنا لضمان عمل الفلترة عبر الـ Ajax بشكل كامل --}}
@if($orders->hasPages())
<div class="card-footer bg-transparent border-top mt-3" style="border-color: var(--border-color) !important;">
    <div class="d-flex justify-content-center">
        {!! $orders->links('pagination::bootstrap-5') !!}
    </div>
</div>
@endif
</div>