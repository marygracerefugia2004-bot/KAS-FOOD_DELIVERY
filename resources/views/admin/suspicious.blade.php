@extends('layouts.app')
@section('title', 'Suspicious Activity')
@section('sl-security', 'active')
@section('content')

<div style="max-width: 1200px; margin: 0 auto; padding: 1rem;">
    <div class="page-hdr">
        <div class="page-hdr-left">
            <h1>Suspicious Activity</h1>
            <p>Monitor failed login attempts and suspicious IP addresses</p>
        </div>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>IP Address</th>
                        <th>Failed Attempts</th>
                        <th>Last Seen</th>
                        <th>Risk Level</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>
                            <code>{{ $log->ip_address }}</code>
                         </div>
                        </td>
                        <td>
                            <span class="badge badge-danger">{{ $log->attempts }} attempts</span>
                        </div>
                        </td>
                        <td>{{ $log->last_seen }}</div>
                        </td>
                        <td>
                            @if($log->attempts > 50)
                                <span class="badge badge-danger">High Risk</span>
                            @elseif($log->attempts > 20)
                                <span class="badge badge-warning">Medium Risk</span>
                            @else
                                <span class="badge badge-info">Low Risk</span>
                            @endif
                         </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 3rem;">
                            <i class="fas fa-shield-alt" style="font-size: 3rem; display: block; margin-bottom: 1rem; color: var(--text-muted);"></i>
                            <p>No suspicious activity detected.</p>
                            <p style="font-size: 0.85rem;">All systems are secure.</p>
                         </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div style="margin-top: 1.5rem;">
            {{ $logs->links() }}
        </div>
    </div>
</div>

@endsection