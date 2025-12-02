<div class="text-xs">
    <div class="flex items-center gap-1 text-success-600 dark:text-success-400">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
        <span>Masuk: {{ $in }}</span>
    </div>
    <div class="flex items-center gap-1 text-danger-600 dark:text-danger-400 mt-0.5">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
        <span>Keluar: {{ $out }}</span>
    </div>
</div>