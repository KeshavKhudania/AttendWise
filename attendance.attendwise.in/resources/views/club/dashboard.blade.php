@extends('layouts.club')

@section('header-title', 'Dashboard')
@section('header-subtitle', 'Welcome back, ' . explode(' ', $manager->name)[0] . '! Manage your club events and members.')

@section('styles')
<style>
    .modern-card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: 1rem;
        padding: 1.75rem;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02), 0 2px 4px -2px rgba(0,0,0,0.02);
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }
    
    .modern-card:hover {
        border-color: var(--text-muted);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05), 0 4px 6px -4px rgba(0,0,0,0.05);
    }

    .stat-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        transition: transform 0.2s ease;
    }
    
    .modern-card:hover .stat-icon-wrapper {
        transform: translateY(-2px);
    }

    .stat-icon-wrapper.success {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .stat-icon-wrapper.warning {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.4s ease-out forwards;
        opacity: 0;
        transform: translateY(5px);
    }
    
    @keyframes fadeIn {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        gap: 1.5rem;
        margin-top: 2rem;
    }
    
    .col-span-8 {
        grid-column: span 8;
    }
    
    .col-span-4 {
        grid-column: span 4;
    }
    
    @media (max-width: 1024px) {
        .col-span-8, .col-span-4 {
            grid-column: span 12;
        }
    }

    .btn-modern {
        background: var(--text-main);
        color: var(--bg);
        border: 1px solid transparent;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 500;
        font-size: 0.8rem;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-modern:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }
</style>
@endsection

@section('content')
<div class="stats-grid">
    <div class="modern-card animate-fade-in" style="animation-delay: 0.1s;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <div style="color: var(--text-muted); font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Total Members</div>
                <div style="font-size: 2.5rem; font-weight: 800; color: var(--text-main); margin-top: 0.5rem; line-height: 1;">
                    {{ $totalMembers }}
                </div>
            </div>
            <div class="stat-icon-wrapper">
                <i data-lucide="users" style="width: 24px; height: 24px;"></i>
            </div>
        </div>
    </div>

    <div class="modern-card animate-fade-in" style="animation-delay: 0.2s;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <div style="color: var(--text-muted); font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Upcoming Events</div>
                <div style="font-size: 2.5rem; font-weight: 800; color: var(--text-main); margin-top: 0.5rem; line-height: 1;">
                    {{ count($events) }}
                </div>
            </div>
            <div class="stat-icon-wrapper success">
                <i data-lucide="calendar" style="width: 24px; height: 24px;"></i>
            </div>
        </div>
    </div>

    <div class="modern-card animate-fade-in" style="animation-delay: 0.3s;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <div style="color: var(--text-muted); font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Status</div>
                <div style="font-size: 2.5rem; font-weight: 800; color: var(--text-main); margin-top: 0.5rem; line-height: 1;">
                    {{ $club->status ? 'Active' : 'Inactive' }}
                </div>
            </div>
            <div class="stat-icon-wrapper {{ $club->status ? 'success' : 'warning' }}">
                <i data-lucide="activity" style="width: 24px; height: 24px;"></i>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    <div class="col-span-8">
        <div class="modern-card animate-fade-in" style="animation-delay: 0.4s; padding: 0;">
            <div style="padding: 1.5rem 1.75rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1.1rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="clock" style="width: 18px; color: var(--text-muted);"></i>
                    Recent Activity
                </h3>
            </div>
            <div style="padding: 1.25rem;">
                <div style="padding: 4rem 2rem; text-align: center;">
                    <div style="width: 64px; height: 64px; background: var(--subtle-bg); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; border: 1px solid var(--border);">
                        <i data-lucide="activity" style="width: 32px; height: 32px; color: var(--text-muted);"></i>
                    </div>
                    <h4 style="font-size: 1.1rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.5rem;">No recent activity</h4>
                    <p style="font-size: 0.9rem; color: var(--text-muted);">Get started by creating an event or managing members.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-4">
        <div class="modern-card animate-fade-in" style="animation-delay: 0.5s;">
            <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem;">Quick Actions</h3>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <a href="#" class="btn-modern" style="justify-content: center;"><i data-lucide="plus"></i> Create Event</a>
                <a href="#" class="btn-modern" style="justify-content: center; background: transparent; color: var(--text-main); border: 1px solid var(--border);"><i data-lucide="user-plus"></i> Invite Member</a>
            </div>
        </div>
    </div>
</div>
@endsection
