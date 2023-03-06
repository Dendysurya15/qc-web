<body>
    @foreach ($dataSkor as $key3 => $item3)
    @php
    // Mutu Transport Wilayah
    $bt_total_wil = 0;
    $tph_total_wil = 0;
    $bt_tph_total_wil = 0;
    $jjg_total_wil = 0;
    $jjg_tph_total_wil = 0;

    // Mutu Buah Wilayah
    $blok_mb = 0;
    $alas_mb = 0;
    $tot_jjg_wil = 0;
    $tot_mentah_wil = 0;
    $tot_matang_wil = 0;
    $tot_over_wil = 0;
    $tot_empty_wil = 0;
    $tot_vcut_wil = 0;
    $tot_abr_wil = 0;
    $tot_krg_wil = 0;
    $tot_Permentah_wil = 0;
    $tot_Permatang_wil = 0;
    $tot_Perover_wil = 0;
    $tot_Perjangkos_wil = 0;
    $tot_Pervcut_wil = 0;
    $tot_Perabr_wil = 0;
    $tot_Perkrg_wil = 0;

    // Mutu Ancak Wilayah
    $jml_pokok_sm_wil = 0;
    $luas_ha_wil = 0;
    $jml_jjg_panen_wil = 0;
    $jml_brtp_wil = 0;
    $jml_brtk_wil = 0;
    $jml_brtgl_wil = 0;
    $jml_bhts_wil = 0;
    $jml_bhtm1_wil = 0;
    $jml_bhtm2_wil = 0;
    $jml_bhtm3_wil = 0;
    $jml_ps_wil = 0;
    $btr_jjg_ma_wil = 0;
    $jjg_tgl_ma_wil = 0;
    @endphp
    @foreach ($dataSkor[$key3] as $key => $item)
    @if (is_array($item))
    @foreach ($item as $key2 => $value)
    @if (is_array($value))
    @php
    $bt_total_wil += check_array('bt_total', $value);
    $tph_total_wil += check_array('tph_sample', $value);
    $jjg_total_wil += check_array('restan_total', $value);

    $blok_mb += check_array('blok_mb', $value);
    $alas_mb += check_array('alas_mb', $value);
    $tot_jjg_wil += check_array('jml_janjang', $value);
    $tot_mentah_wil += check_array('jml_mentah', $value);
    $tot_matang_wil += check_array('jml_masak', $value);
    $tot_over_wil += check_array('jml_over', $value);
    $tot_empty_wil += check_array('jml_empty', $value);
    $tot_abr_wil += check_array('jml_abnormal', $value);
    $tot_vcut_wil += check_array('jml_vcut', $value);

    $jml_pokok_sm_wil += check_array('jml_pokok_sampel', $value);
    $luas_ha_wil += check_array('luas_ha', $value);
    $jml_jjg_panen_wil += check_array('jml_jjg_panen', $value);
    $jml_brtp_wil += check_array('p_ma', $value);
    $jml_brtk_wil += check_array('k_ma', $value);
    $jml_brtgl_wil += check_array('gl_ma', $value);
    $jml_bhts_wil += check_array('bhts_ma', $value);
    $jml_bhtm1_wil += check_array('bhtm1_ma', $value);
    $jml_bhtm2_wil += check_array('bhtm2_ma', $value);
    $jml_bhtm3_wil += check_array('bhtm3_ma', $value);
    $jml_ps_wil += check_array('ps_ma', $value);

    $totalSkorAkhir = skor_brd_ma(check_array('btr_jjg_ma', $value)) + skor_buah_Ma(check_array('jjg_tgl_ma', $value)) +
            skor_palepah_ma(check_array('PerPSMA', $value)) + skor_brd_tinggal(check_array('skor', $value)) +
            skor_buah_tinggal(check_array('skor_restan', $value)) + skor_buah_mentah_mb(check_array('PersenBuahMentah', $value)) +
            skor_buah_masak_mb(check_array('PersenBuahMasak', $value))
            + skor_buah_over_mb(check_array('PersenBuahOver', $value)) +
            skor_jangkos_mb(check_array('PersenPerJanjang', $value)) +
            skor_buah_over_mb(check_array('PersenVcut', $value)) +
            skor_abr_mb(check_array('PersenKrgBrd', $value));
    $skor_kategori_akhir = skor_kategori_akhir($totalSkorAkhir);
    @endphp
    <tr>
        {{-- Bagian Mutu Transport --}}
        <td>{{$key}}</td>
        <td>{{$key2}}</td>
        <td>{{check_array('jml_pokok_sampel', $value)}}</td>
        <td>{{check_array('luas_ha', $value)}}</td>
        <td>{{check_array('jml_jjg_panen', $value)}}</td>
        <td>{{check_array('akp_real', $value)}}</td>
        <td>{{check_array('p_ma', $value)}}</td>
        <td>{{check_array('k_ma', $value)}}</td>
        <td>{{check_array('gl_ma', $value)}}</td>
        <td>{{check_array('total_brd_ma', $value)}}</td>
        <td>{{check_array('btr_jjg_ma', $value)}}</td>
        <td>{{skor_brd_ma(check_array('btr_jjg_ma', $value))}}</td>
        <td>{{check_array('bhts_ma', $value)}}</td>
        <td>{{check_array('bhtm1_ma', $value)}}</td>
        <td>{{check_array('bhtm2_ma', $value)}}</td>
        <td>{{check_array('bhtm3_ma', $value)}}</td>
        <td>{{check_array('tot_jjg_ma', $value)}}</td>
        <td>{{check_array('jjg_tgl_ma', $value)}}</td>
        <td>{{skor_buah_Ma(check_array('jjg_tgl_ma', $value))}}</td>
        <td>{{check_array('ps_ma', $value)}}</td>
        <td>{{check_array('PerPSMA', $value)}}</td>
        <td>{{skor_palepah_ma(check_array('PerPSMA', $value))}}</td>
        <td>{{skor_brd_ma(check_array('btr_jjg_ma', $value)) + skor_buah_Ma(check_array('jjg_tgl_ma', $value)) + skor_palepah_ma(check_array('PerPSMA', $value))}}</td>
        <td>{{check_array('tph_sample', $value)}}</td>
        <td>{{check_array('bt_total', $value)}}</td>
        <td>{{check_array('skor', $value)}}</td>
        <td>{{skor_brd_tinggal(check_array('skor', $value))}}</td>
        <td>{{check_array('restan_total', $value)}}</td>
        <td>{{check_array('skor_restan', $value)}}</td>
        <td>{{skor_buah_tinggal(check_array('skor_restan', $value))}}</td>
        <td>{{ skor_brd_tinggal(check_array('skor', $value)) +
            skor_buah_tinggal(check_array('skor_restan', $value)) }}</td>
        {{-- Bagian Mutu Buah - Buah Mentah --}}
        <td>{{check_array('jml_janjang', $value)}}</td>
        <td>{{check_array('jml_mentah', $value)}}</td>
        <td>{{check_array('PersenBuahMentah', $value)}}</td>
        <td>{{skor_buah_mentah_mb(check_array('PersenBuahMentah', $value))}}</td>
        {{-- Bagian Mutu Buah - Buah Matang --}}
        <td>{{check_array('jml_masak', $value)}}</td>
        <td>{{check_array('PersenBuahMasak', $value)}}</td>
        <td>{{skor_buah_masak_mb(check_array('PersenBuahMasak', $value))}}</td>
        {{-- Bagian Mutu Buah - Lewat Matang --}}
        <td>{{check_array('jml_over', $value)}}</td>
        <td>{{check_array('PersenBuahOver', $value)}}</td>
        <td>{{skor_buah_over_mb(check_array('PersenBuahOver', $value))}}</td>
        {{-- Bagian Mutu Buah - Jangkos --}}
        <td>{{check_array('jml_empty', $value)}}</td>
        <td>{{check_array('PersenPerJanjang', $value)}}</td>
        <td>{{skor_jangkos_mb(check_array('PersenPerJanjang', $value))}}</td>
        {{-- Bagian Mutu Buah - Tidak Standar V-Cut --}}
        <td>{{check_array('jml_vcut', $value)}}</td>
        <td>{{check_array('PersenVcut', $value)}}</td>
        <td>{{skor_buah_over_mb(check_array('PersenVcut', $value))}}</td>
        {{-- Bagian Mutu Buah - Abnormal --}}
        <td>{{check_array('jml_abnormal', $value)}}</td>
        <td>{{check_array('PersenAbr', $value)}}</td>
        {{-- Bagian Mutu Buah - Karung Brondolan --}}
        <td>{{check_array('jml_krg_brd', $value)}}</td>
        <td>{{check_array('PersenKrgBrd', $value)}}</td>
        <td>{{skor_abr_mb(check_array('PersenKrgBrd', $value))}}</td>
        <td>{{skor_buah_mentah_mb(check_array('PersenBuahMentah', $value)) +
            skor_buah_masak_mb(check_array('PersenBuahMasak', $value))
            + skor_buah_over_mb(check_array('PersenBuahOver', $value)) +
            skor_jangkos_mb(check_array('PersenPerJanjang', $value)) +
            skor_buah_over_mb(check_array('PersenVcut', $value)) +
            skor_abr_mb(check_array('PersenKrgBrd', $value))}}</td>
        <td bgcolor="{{ $skor_kategori_akhir[0] }}">{{ $totalSkorAkhir }}</td>
        <td bgcolor="{{ $skor_kategori_akhir[0] }}">{{ $skor_kategori_akhir[1] }}</td>
    </tr>
    @endif
    @endforeach
    @php
    $totalSkorAkhirEst = skor_brd_ma(check_array('btr_jjg_ma_est', $item)) + 
        skor_buah_Ma(check_array('jjg_tgl_ma_est', $item)) + 
        skor_palepah_ma(check_array('PerPSMA_est', $item)) +
        skor_brd_tinggal(check_array('bt_tph_total', $item))
        +skor_buah_tinggal(check_array('jjg_tph_total', $item))+
        skor_buah_mentah_mb(check_array('tot_PersenBuahMentah', $item)) +
        skor_buah_masak_mb(check_array('tot_PersenBuahMasak', $item))
        + skor_buah_over_mb(check_array('tot_PersenBuahOver', $item)) +
        skor_jangkos_mb(check_array('tot_PersenPerJanjang', $item)) +
        skor_buah_over_mb(check_array('tot_PersenVcut', $item)) +
        skor_abr_mb(check_array('tot_PersenKrgBrd', $item));
    $skor_kategori_akhir_est = skor_kategori_akhir($totalSkorAkhirEst);
    @endphp
    <tr>
        <td style="background-color : #b0d48c; color: #000000;" colspan="2">{{$key}}
        </td>
        <td style="background-color : #b0d48c; color: #000000;">{{check_array('tot_jml_pokok_ma', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">{{check_array('tot_luas_ha_ma', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">{{check_array('tot_jml_jjg_panen_ma', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">{{check_array('akp_real_est', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">{{check_array('p_ma_est', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">{{check_array('k_ma_est', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">{{check_array('gl_ma_est', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">{{check_array('total_brd_ma_est', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">{{check_array('btr_jjg_ma_est', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">{{skor_brd_ma(check_array('btr_jjg_ma_est', $item))}}</td>
        <td style="background-color : #b0d48c; color: #000000;">{{check_array('bhts_ma_est', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">{{check_array('bhtm1_ma_est', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">{{check_array('bhtm2_ma_est', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">{{check_array('bhtm3_ma_est', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">{{check_array('tot_jjg_ma_est', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">{{check_array('jjg_tgl_ma_est', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">{{skor_buah_Ma(check_array('jjg_tgl_ma_est', $item))}}</td>
        <td style="background-color : #b0d48c; color: #000000;">{{check_array('ps_ma_est', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">{{check_array('PerPSMA_est', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">{{skor_palepah_ma(check_array('PerPSMA_est', $item))}}</td>
        <td style="background-color : #b0d48c; color: #000000;">{{skor_brd_ma(check_array('btr_jjg_ma_est', $item)) + skor_buah_Ma(check_array('jjg_tgl_ma_est', $item)) + skor_palepah_ma(check_array('PerPSMA_est', $item))}}</td>
        <td style="background-color : #b0d48c; color: #000000;">
            {{check_array('tph_sample_total', $item)}}</td>
        </td>
        <td style="background-color : #b0d48c; color: #000000;">
            {{check_array('bt_total', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">
            {{check_array('bt_tph_total', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">
            {{skor_brd_tinggal(check_array('bt_tph_total', $item)) }}</td>
        <td style="background-color : #b0d48c; color: #000000;">
            {{check_array('jjg_total', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">
            {{check_array('jjg_tph_total', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">
            {{skor_buah_tinggal(check_array('jjg_tph_total', $item)) }}</td>
        <td style="background-color : #b0d48c; color: #000000;">
            {{skor_brd_tinggal(check_array('bt_tph_total',
            $item))+skor_buah_tinggal(check_array('jjg_tph_total', $item))
            }}</td>
        <td style="background-color : #b0d48c; color: #000000;">
            {{check_array('tot_jjg', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">
            {{check_array('tot_mentah', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">
            {{check_array('tot_PersenBuahMentah', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">
            {{skor_buah_mentah_mb(check_array('tot_PersenBuahMentah', $item))}}</td>
        <td style="background-color : #b0d48c; color: #000000;">
            {{check_array('tot_matang', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">
            {{check_array('tot_PersenBuahMasak', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">
            {{skor_buah_masak_mb(check_array('tot_PersenBuahMasak', $item))}}</td>
        <td style="background-color : #b0d48c; color: #000000;">
            {{check_array('tot_over', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">
            {{check_array('tot_PersenBuahOver', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">
            {{skor_buah_over_mb(check_array('tot_PersenBuahOver', $item))}}</td>
        <td style="background-color : #b0d48c; color: #000000;">
            {{check_array('tot_empty', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">
            {{check_array('tot_PersenPerJanjang', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">
            {{skor_jangkos_mb(check_array('tot_PersenPerJanjang', $item))}}</td>
        <td style="background-color : #b0d48c; color: #000000;">
            {{check_array('tot_vcut', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">
            {{check_array('tot_PersenVcut', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">
            {{skor_buah_over_mb(check_array('tot_PersenVcut', $item))}}</td>
        <td style="background-color : #b0d48c; color: #000000;">
            {{check_array('tot_abr', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">
            {{check_array('tot_PersenAbr', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">
            {{check_array('tot_krg_brd', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">
            {{check_array('tot_PersenKrgBrd', $item)}}</td>
        <td style="background-color : #b0d48c; color: #000000;">
            {{skor_abr_mb(check_array('tot_PersenKrgBrd', $item))}}</td>
        <td style="background-color : #b0d48c; color: #000000;">
            {{skor_buah_mentah_mb(check_array('tot_PersenBuahMentah', $item)) +
            skor_buah_masak_mb(check_array('tot_PersenBuahMasak', $item))
            + skor_buah_over_mb(check_array('tot_PersenBuahOver', $item)) +
            skor_jangkos_mb(check_array('tot_PersenPerJanjang', $item)) +
            skor_buah_over_mb(check_array('tot_PersenVcut', $item)) +
            skor_abr_mb(check_array('tot_PersenKrgBrd', $item))}}</td>
        <td style="background-color : {{$skor_kategori_akhir_est[0]}}; color: #000000;">{{$totalSkorAkhirEst}}</td>
        <td style="background-color : {{$skor_kategori_akhir_est[0]}}; color: #000000;">{{$skor_kategori_akhir_est[1]}}</td>
    </tr>
    @php
    $bt_tph_total_wil = $tph_total_wil == 0 ? $bt_total_wil : round($bt_total_wil / $tph_total_wil, 2);
    $jjg_tph_total_wil = $tph_total_wil == 0 ? $jjg_total_wil : round($jjg_total_wil / $tph_total_wil, 2);
    $tot_krg_wil = $alas_mb == 0 ? $blok_mb : round($blok_mb / $alas_mb, 2);
    $tot_Permentah_wil = ($tot_jjg_wil - $tot_abr_wil) == 0 ? $tot_mentah_wil : round(($tot_mentah_wil / ($tot_jjg_wil - $tot_abr_wil)) * 100, 2);
    $tot_Permatang_wil = ($tot_jjg_wil - $tot_abr_wil) == 0 ? $tot_matang_wil : round(($tot_matang_wil / ($tot_jjg_wil - $tot_abr_wil)) * 100, 2);
    $tot_Perover_wil = ($tot_jjg_wil - $tot_abr_wil) == 0 ? $tot_over_wil : round(($tot_over_wil / ($tot_jjg_wil - $tot_abr_wil)) * 100, 2);
    $tot_Perjangkos_wil = ($tot_jjg_wil - $tot_abr_wil) == 0 ? $tot_empty_wil : round(($tot_empty_wil / ($tot_jjg_wil - $tot_abr_wil)) * 100, 2);
    $tot_Pervcut_wil = count_percent($tot_vcut_wil, $tot_jjg_wil);
    $tot_Perabr_wil = count_percent($tot_abr_wil, $tot_jjg_wil);
    $tot_Perkrg_wil = count_percent($blok_mb, $alas_mb);

    $akp_real_wil = count_percent($jml_jjg_panen_wil, $jml_pokok_sm_wil);
    $tot_brd_wil = $jml_brtp_wil + $jml_brtk_wil + $jml_brtgl_wil;
    $btr_jjg_ma_wil = $jml_jjg_panen_wil == 0 ? $tot_brd_wil : round(($tot_brd_wil / $jml_jjg_panen_wil), 2);
    $tot_bt_wil = $jml_bhts_wil + $jml_bhtm1_wil + $jml_bhtm2_wil + $jml_bhtm3_wil;
    $bt_jjg_ma_wil = ($jml_jjg_panen_wil + $tot_bt_wil) == 0 ? $tot_bt_wil : round(($tot_bt_wil / ($jml_jjg_panen_wil + $tot_bt_wil)) * 100, 2);
    $PerPSMA_wil = count_percent($jml_ps_wil, $jml_pokok_sm_wil);
    @endphp
    @endif
    @endforeach
    @php
    $totalSkorAkhirWil = skor_brd_ma($btr_jjg_ma_wil) + 
        skor_buah_Ma($bt_jjg_ma_wil) + 
        skor_palepah_ma($PerPSMA_wil) + 
        skor_brd_tinggal($bt_tph_total_wil)+
        skor_buah_tinggal($jjg_tph_total_wil)+
        skor_buah_mentah_mb($tot_Permentah_wil) +
        skor_buah_masak_mb($tot_Permatang_wil) +
        skor_buah_over_mb($tot_Perover_wil) +
        skor_jangkos_mb($tot_Perjangkos_wil) +
        skor_buah_over_mb($tot_Pervcut_wil) + skor_abr_mb($tot_Perkrg_wil);
    $skor_kategori_akhir_wil = skor_kategori_akhir($totalSkorAkhirWil);
    @endphp
    <tr>
        <td style="background-color : yellow; color: #000000;" colspan="2">
            WIL-{{$key3}}
        </td>
        <td style="background-color : yellow; color: #000000;">{{ $jml_pokok_sm_wil }}</td>
        <td style="background-color : yellow; color: #000000;">{{ $luas_ha_wil }}</td>
        <td style="background-color : yellow; color: #000000;">{{ $jml_jjg_panen_wil }}</td>
        <td style="background-color : yellow; color: #000000;">{{ $akp_real_wil }}</td>
        <td style="background-color : yellow; color: #000000;">{{ $jml_brtp_wil }}</td>
        <td style="background-color : yellow; color: #000000;">{{ $jml_brtk_wil }}</td>
        <td style="background-color : yellow; color: #000000;">{{ $jml_brtgl_wil }}</td>
        <td style="background-color : yellow; color: #000000;">{{ $tot_brd_wil }}</td>
        <td style="background-color : yellow; color: #000000;">{{ $btr_jjg_ma_wil }}</td>
        <td style="background-color : yellow; color: #000000;">{{skor_brd_ma($btr_jjg_ma_wil)}}</td>
        <td style="background-color : yellow; color: #000000;">{{ $jml_bhts_wil }}</td>
        <td style="background-color : yellow; color: #000000;">{{ $jml_bhtm1_wil }}</td>
        <td style="background-color : yellow; color: #000000;">{{ $jml_bhtm2_wil }}</td>
        <td style="background-color : yellow; color: #000000;">{{ $jml_bhtm3_wil }}</td>
        <td style="background-color : yellow; color: #000000;">{{ $tot_bt_wil }}</td>
        <td style="background-color : yellow; color: #000000;">{{ $bt_jjg_ma_wil }}</td>
        <td style="background-color : yellow; color: #000000;">{{skor_buah_Ma($bt_jjg_ma_wil)}}</td>
        <td style="background-color : yellow; color: #000000;">{{ $jml_ps_wil }}</td>
        <td style="background-color : yellow; color: #000000;">{{ $PerPSMA_wil }}</td>
        <td style="background-color : yellow; color: #000000;">{{skor_palepah_ma($PerPSMA_wil)}}</td>
        <td style="background-color : yellow; color: #000000;">{{skor_brd_ma($btr_jjg_ma_wil) + skor_buah_Ma($bt_jjg_ma_wil) + skor_palepah_ma($PerPSMA_wil)}}</td>
        <td style="background-color : yellow; color: #000000;">{{ $tph_total_wil }}
        </td>
        <td style="background-color : yellow; color: #000000;">{{ $bt_total_wil }}
        </td>
        <td style="background-color : yellow; color: #000000;">{{
            $bt_tph_total_wil}}
        </td>
        <td style="background-color : yellow; color: #000000;">{{
            skor_brd_tinggal($bt_tph_total_wil) }}
        </td>
        <td style="background-color : yellow; color: #000000;">{{ $jjg_total_wil }}
        </td>
        <td style="background-color : yellow; color: #000000;">{{
            $jjg_tph_total_wil}}
        </td>
        <td style="background-color : yellow; color: #000000;">{{
            skor_buah_tinggal($jjg_tph_total_wil) }}
        </td>
        <td style="background-color : yellow; color: #000000;">{{
            skor_brd_tinggal($bt_tph_total_wil)+skor_buah_tinggal($jjg_tph_total_wil)}}
        </td>
        <td style="background-color : yellow; color: #000000;">{{
            $tot_jjg_wil}}
        </td>
        <td style="background-color : yellow; color: #000000;">{{
            $tot_mentah_wil}}
        </td>
        <td style="background-color : yellow; color: #000000;">{{
            $tot_Permentah_wil}}
        </td>
        <td style="background-color : yellow; color: #000000;">
            {{skor_buah_mentah_mb($tot_Permentah_wil)}}</td>
        <td style="background-color : yellow; color: #000000;">{{
            $tot_matang_wil}}
        </td>
        <td style="background-color : yellow; color: #000000;">{{
            $tot_Permatang_wil}}
        </td>
        <td style="background-color : yellow; color: #000000;">
            {{skor_buah_masak_mb($tot_Permatang_wil)}}</td>
        <td style="background-color : yellow; color: #000000;">{{
            $tot_over_wil}}
        </td>
        <td style="background-color : yellow; color: #000000;">{{
            $tot_Perover_wil}}
        </td>
        <td style="background-color : yellow; color: #000000;">
            {{skor_buah_over_mb($tot_Perover_wil)}}</td>
        <td style="background-color : yellow; color: #000000;">{{
            $tot_empty_wil}}
        </td>
        <td style="background-color : yellow; color: #000000;">{{
            $tot_Perjangkos_wil}}
        </td>
        <td style="background-color : yellow; color: #000000;">
            {{skor_jangkos_mb($tot_Perjangkos_wil)}}</td>
        <td style="background-color : yellow; color: #000000;">{{
            $tot_vcut_wil}}
        </td>
        <td style="background-color : yellow; color: #000000;">{{
            $tot_Pervcut_wil}}
        </td>
        <td style="background-color : yellow; color: #000000;">
            {{skor_buah_over_mb($tot_Pervcut_wil)}}</td>
        <td style="background-color : yellow; color: #000000;">{{
            $tot_abr_wil}}
        </td>
        <td style="background-color : yellow; color: #000000;">{{
            $tot_Perabr_wil}}
        </td>
        <td style="background-color : yellow; color: #000000;">{{
            $tot_krg_wil}}
        </td>
        <td style="background-color : yellow; color: #000000;">{{
            $tot_Perkrg_wil}}
        </td>
        <td style="background-color : yellow; color: #000000;">
            {{skor_abr_mb($tot_Perkrg_wil)}}</td>
        <td style="background-color : yellow; color: #000000;">
            {{skor_buah_mentah_mb($tot_Permentah_wil) +
            skor_buah_masak_mb($tot_Permatang_wil) +
            skor_buah_over_mb($tot_Perover_wil) +
            skor_jangkos_mb($tot_Perjangkos_wil) +
            skor_buah_over_mb($tot_Pervcut_wil) + skor_abr_mb($tot_Perkrg_wil)}}
        </td>
        <td style="background-color : {{ $skor_kategori_akhir_wil[0] }}; color: #000000;">{{ $totalSkorAkhirWil }}</td>
        <td style="background-color : {{ $skor_kategori_akhir_wil[0] }}; color: #000000;">{{ $skor_kategori_akhir_wil[1] }}</td>
    </tr>
    @if ($key3 === array_key_last($dataSkor))
    @else
    <tr style="border: none;">
        <td colspan="32" style="background-color : #fff;">&nbsp;</td>
    </tr>
    @endif
    @endforeach
</body>