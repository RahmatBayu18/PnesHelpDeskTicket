@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            {{--
                Menggunakan asset() akan menghasilkan URL lengkap (misal: https://domainmu.com/aset/logo.svg).
                Ini wajib untuk email karena Gmail tidak bisa membaca path relative.
            --}}
            <img src="{{ asset('aset/logo-PensHelpDes1.svg') }}" 
                 class="logo" 
                 alt="{{ config('app.name') }}" 
                 style="height: 60px; width: auto; border: 0;">
        </a>
    </td>
</tr>