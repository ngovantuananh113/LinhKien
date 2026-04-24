@push('head')
    <style>
        .admin-orders-scrollbar::-webkit-scrollbar { height: 6px; width: 6px; }
        .admin-orders-scrollbar::-webkit-scrollbar-track { background: #131313; }
        .admin-orders-scrollbar::-webkit-scrollbar-thumb { background: linear-gradient(90deg, #7b2ff7, #00f4fe); }
        /* Dropdown trạng thái — viền neon, monospace, glow khi focus */
        .aos-select-wrap {
            position: relative;
            display: inline-block;
            vertical-align: middle;
        }
        .aos-select-wrap::before {
            content: '';
            position: absolute;
            inset: -1px;
            border: 1px solid rgba(0, 244, 254, 0.45);
            pointer-events: none;
            z-index: 0;
            box-shadow:
                0 0 16px rgba(0, 244, 254, 0.14),
                inset 0 0 24px rgba(123, 47, 247, 0.06);
            transition: box-shadow 0.25s ease, border-color 0.25s ease;
        }
        .aos-select-wrap:focus-within::before {
            border-color: rgba(0, 244, 254, 0.85);
            box-shadow:
                0 0 24px rgba(0, 244, 254, 0.28),
                0 0 48px rgba(123, 47, 247, 0.12),
                inset 0 0 32px rgba(123, 47, 247, 0.1);
        }
        .aos-select-wrap .aos-corner-tl,
        .aos-select-wrap .aos-corner-br {
            position: absolute;
            width: 6px;
            height: 6px;
            pointer-events: none;
            z-index: 2;
            border-color: rgba(0, 244, 254, 0.65);
        }
        .aos-select-wrap .aos-corner-tl {
            top: -1px;
            left: -1px;
            border-top: 2px solid;
            border-left: 2px solid;
        }
        .aos-select-wrap .aos-corner-br {
            right: -1px;
            bottom: -1px;
            border-bottom: 2px solid;
            border-right: 2px solid;
        }
        .aos-select {
            position: relative;
            z-index: 1;
            display: block;
            width: 100%;
            appearance: none;
            -webkit-appearance: none;
            background-color: #141414;
            color: #f0ece8;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-weight: 700;
            font-size: 0.6875rem;
            line-height: 1.25;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            padding: 0.7rem 2.35rem 0.7rem 0.85rem;
            border: none;
            outline: none;
            cursor: pointer;
            min-height: 2.5rem;
        }
        .aos-select-wrap--sm .aos-select {
            font-size: 0.625rem;
            letter-spacing: 0.1em;
            padding: 0.45rem 1.85rem 0.45rem 0.55rem;
            min-height: 2rem;
        }
        .aos-select-wrap--sm::before {
            inset: -1px;
            box-shadow: 0 0 10px rgba(0, 244, 254, 0.1);
        }
        .aos-select:hover {
            background-color: #181818;
        }
        .aos-select option {
            background-color: #1f1f1f;
            color: #e5e2e1;
            font-weight: 700;
            padding: 0.5rem;
        }
        .aos-select-icon {
            position: absolute;
            right: 0.35rem;
            top: 50%;
            transform: translateY(-50%);
            z-index: 2;
            pointer-events: none;
            color: #00f4fe;
            opacity: 0.85;
            font-size: 1.15rem !important;
        }
        .aos-select-wrap--sm .aos-select-icon {
            font-size: 1rem !important;
            right: 0.2rem;
        }
    </style>
@endpush
