@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            <img src="{{ asset('aset/Logo-PensHelpDes.png') }}" 
                 class="logo" 
                 alt="{{ config('app.name') }}" 
                 style="height: 60px; width: auto; border: 0;">
        </a>
    </td>
</tr>