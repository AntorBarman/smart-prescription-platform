<template>
    <div class="min-h-screen bg-[#F8FAFC] flex items-center justify-center px-4">
        <div class="max-w-md w-full bg-white rounded-2xl border border-slate-200 shadow-sm p-8 text-center">
            <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <h1 class="text-xl font-semibold text-slate-900">Verify Your Email</h1>
            <p class="text-sm text-slate-500 mt-2">
                We've sent a verification link to <strong>{{ $page.props.auth.user.email }}</strong>.
                Please check your inbox.
            </p>
            
            <form @submit.prevent="resend" class="mt-6">
                <button type="submit" class="w-full py-2.5 px-4 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition">
                    Resend Verification Email
                </button>
            </form>

            <!-- Login Link -->
            <Link href="/login" class="block mt-3 w-full py-2.5 px-4 border border-slate-300 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-50 transition">
                Go to Login
            </Link>

            <form @submit.prevent="logout" class="mt-3">
                <button type="submit" class="w-full py-2.5 px-4 text-slate-400 text-sm font-medium hover:text-slate-600 transition">
                    Logout
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';

const resend = () => {
    router.post('/email/verification-notification', {}, {
        onSuccess: () => {
            alert('Verification email sent!');
        },
    });
};

const logout = () => {
    router.post('/logout');
};
</script>