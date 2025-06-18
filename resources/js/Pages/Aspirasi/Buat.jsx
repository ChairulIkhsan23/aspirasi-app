import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { useForm } from '@inertiajs/react';

export default function BuatAspirasi({ auth, topiks }) {
    const { data, setData, post, processing, errors } = useForm({
        judul: '',
        isi: '',
        topik_id: '',
        is_anonim: false,
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('aspirasi.store'));
    };

    return (
        <AuthenticatedLayout user={auth.user}>
            <h1 className="mb-4 text-xl font-bold">Kirim Aspirasi</h1>
            <form onSubmit={submit} className="space-y-4">
                <div>
                    <label>Judul</label>
                    <input
                        value={data.judul}
                        onChange={(e) => setData('judul', e.target.value)}
                    />
                    {errors.judul && <div>{errors.judul}</div>}
                </div>

                <div>
                    <label>Topik</label>
                    <select
                        value={data.topik_id}
                        onChange={(e) => setData('topik_id', e.target.value)}
                    >
                        <option value="">-- Pilih Topik --</option>
                        {topiks.map((topik) => (
                            <option key={topik.id} value={topik.id}>
                                {topik.nama}
                            </option>
                        ))}
                    </select>
                    {errors.topik_id && <div>{errors.topik_id}</div>}
                </div>

                <div>
                    <label>Isi Aspirasi</label>
                    <textarea
                        value={data.isi}
                        onChange={(e) => setData('isi', e.target.value)}
                    />
                    {errors.isi && <div>{errors.isi}</div>}
                </div>

                <label>
                    <input
                        type="checkbox"
                        checked={data.is_anonim}
                        onChange={(e) => setData('is_anonim', e.target.checked)}
                    />
                    Kirim sebagai anonim
                </label>

                <button type="submit" disabled={processing}>
                    Kirim Aspirasi
                </button>
            </form>
        </AuthenticatedLayout>
    );
}
