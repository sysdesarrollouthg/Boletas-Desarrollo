<style>
    .cv-wrapper {
    max-width: 1100px;
    margin: 0;
    padding: 0.5rem 1.5rem 2rem;
}

.cv-select {
    background: #f7f8fc;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 0 36px 0 14px;
    height: 44px;
    font-size: .92rem;
    font-weight: 600;
    color: #2d3748;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
    appearance: none;
    width: 100%;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%234e73df' stroke-width='2' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
}

.cv-select:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.15);
    background-color: #fff;
}

.cv-label,
.pt-label {
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .07em;
    color: #718096;
    margin-top: 10px;
    display: block;
}

.pt-select {
    width: 100%;
    background: #f7f8fc;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 0 36px 0 14px;
    height: 44px;
    font-size: .92rem;
    font-weight: 600;
    color: #2d3748;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%234e73df' stroke-width='2' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
}

.pt-select:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.15);
    background-color: #fff;
}

.pt-input {
    width: 100%;
    background: #f7f8fc;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 0 14px;
    height: 44px;
    font-size: .92rem;
    font-weight: 600;
    color: #2d3748;
    outline: none;
    transition: border-color .2s, box-shadow .2s, background .2s;
}

.pt-input:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.15);
    background: #fff;
}

.pt-input::placeholder {
    color: #cbd5e0;
    font-weight: 400;
}

.pt-prefix-wrap {
    display: flex;
    align-items: center;
    background: #f7f8fc;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
    transition: border-color .2s, box-shadow .2s;
}

.pt-prefix-wrap:focus-within {
    border-color: #4e73df;
    box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.15);
    background: #fff;
}

.pt-prefix {
    padding: 0 12px;
    font-size: .78rem;
    font-weight: 800;
    color: #4e73df;
    height: 44px;
    display: flex;
    align-items: center;
    background: #eef1fb;
    border-right: 2px solid #e2e8f0;
    white-space: nowrap;
}

.pt-prefix-wrap:focus-within .pt-prefix {
    border-right-color: #4e73df;
}

.pt-input-prefixed {
    flex: 1;
    border: none;
    background: transparent;
    padding: 0 14px;
    height: 44px;
    font-size: .92rem;
    font-weight: 600;
    color: #2d3748;
    outline: none;
}

.pt-input-prefixed::placeholder {
    color: #cbd5e0;
    font-weight: 400;
}

.pt-btn-outline {
    border: 2px solid #4e73df;
    background: #fff;
    color: #4e73df;
    border-radius: 10px;
    padding: 0 14px;
    height: 44px;
    font-size: .8rem;
    font-weight: 700;
    cursor: pointer;
    white-space: nowrap;
    transition: background .2s, color .2s;
    display: inline-flex;
    align-items: center;
    flex-shrink: 0;
}

.pt-btn-outline:hover {
    background: #4e73df;
    color: #fff;
}

.pt-btn-gray {
    border-color: #a0aec0;
    color: #718096;
}

.pt-btn-gray:hover {
    background: #718096;
    color: #fff;
    border-color: #718096;
}

.pt-btn-primary {
    background: linear-gradient(135deg, #4e73df, #224abe);
    border: none;
    color: #fff;
    border-radius: 10px;
    padding: 0 20px;
    height: 44px;
    font-size: .88rem;
    font-weight: 700;
    cursor: pointer;
    white-space: nowrap;
    box-shadow: 0 4px 14px rgba(78, 115, 223, 0.35);
    transition: opacity .2s, box-shadow .2s;
    display: inline-flex;
    align-items: center;
    flex-shrink: 0;
}

.pt-btn-primary:hover {
    opacity: .92;
    box-shadow: 0 6px 20px rgba(78, 115, 223, 0.45);
}

.pt-badge-wrap {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-top: 8px;
}

.pt-badge-fecha {
    background: linear-gradient(135deg, #ff6b6b, #ee5a24);
    color: #fff;
    font-weight: 700;
    font-size: .82rem;
    padding: 3px 12px;
    border-radius: 20px;
    white-space: nowrap;
}

.cv-divider {
    height: 1px;
    background: #e2e8f0;
    margin: 2rem 0;
}

.ar-th {
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: #718096;
    padding: 8px 10px;
    background: #f8f9fc;
    border-bottom: 2px solid #e2e8f0;
}

.ar-td {
    font-size: .88rem;
    padding: 8px 10px;
    color: #4a5568;
    border-bottom: 1px solid #f0f4ff;
}

.ar-td-mono {
    font-family: monospace;
    font-weight: 700;
    color: #2d3748;
}

.menu-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: #f1f3f8;
    color: #718096;
    border: 2px solid #e2e8f0;
    border-radius: 9px;
    padding: 0 16px;
    height: 36px;
    font-size: .82rem;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    transition: background .2s, color .2s;
}

.menu-btn:hover:not(:disabled) {
    background: #e2e8f0;
    color: #4a5568;
}

.menu-btn:disabled,
.menu-btn.active:disabled {
    cursor: default;
    background: linear-gradient(135deg,#4e73df,#224abe);
    color: #fff !important;
    border: none;
    box-shadow: 0 3px 10px rgba(78,115,223,0.3);
}

.menu-btn.active {
    background: #e2e8f0;
    color: #4a5568;
}
</style>

