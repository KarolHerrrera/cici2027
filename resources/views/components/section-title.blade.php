{{-- Encabezado de sección reutilizable:
     <x-section-title kicker="Fechas" title="Fechas importantes" subtitle="Texto opcional" /> --}}
@props(['kicker' => null, 'title', 'subtitle' => null])

<div class="section-title">
    @if ($kicker)
        <span class="section-title__kicker">{{ $kicker }}</span>
    @endif
    <h2>{{ $title }}</h2>
    @if ($subtitle)
        <p>{{ $subtitle }}</p>
    @endif
</div>
