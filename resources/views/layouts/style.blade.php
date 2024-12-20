<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    :root {
        --primary_color: <?=setting('general.primary_color') ?>;
        --secondary_color: <?=setting('general.secondary_color') ?>;
        --tertiary_color: <?=setting('general.third_color') ?>;
    }

    .primary-background {
        background-color: rgb(239, 241, 255);
        color: var(--tertiary_color)
    }

    .primary-button {
        background-color: var(--tertiary_color);
        color: var(--secondary_color);
    }

    .primary-button:hover {
        color: var(--primary_color);
        background-color: var(--tertiary_color) !important;
    }

    .primary-button.active {
        color: var(--secondary_color);
        background: var(--primary_color);
        transition: all 0.2s ease-in-out;
        border: none;
    }

    .primary-button.active:hover {
        color: var(--tertiary_color) !important;
        background: var(--primary_color) !important;
        border: none !important;
    }

    .primary-text {
        color: var(--tertiary_color);
    }

    .secondary-background {
        background-color: var(--tertiary_color);
        color: var(--secondary_color);
    }

    .tertiary-background {
        background: var(--tertiary_color);
        color: var(--secondary_color);
    }

    .link-primary {
        color: var(--primary_color) !important;
    }

    .link-primary:hover {
        color: var(--tertiary_color) !important;
    }

    .link-secondary {
        color: var(--tertiary_color) !important;
    }

    .link-secondary:hover {
        color: var(--primary_color) !important;
    }

    .btn {
        border-radius: 50px !important;
    }

    nav .link-primary:hover,
    nav .link-primary.active {
        color: var(--primary_color) !important;
        height: 100%;
        padding-bottom: 7px;
        font-weight: medium;
        border-bottom: 2px solid var(--primary_color);
        margin-bottom: 0px;
    }

    .fs-10 {
        font-size: 10px;
    }

    .fs-12 {
        font-size: 12px;
    }

    .fs-14 {
        font-size: 14px;
    }

    .fs-16 {
        font-size: 16px;
    }

    .fs-18 {
        font-size: 18px;
    }

    .fs-20 {
        font-size: 20px;
    }

    p {
        margin-bottom: 0px;
    }

    .backgound-light {
        background: rgb(167, 167, 167);
    }

    input:focus,
    select:focus,
    button:focus,
    textarea:focus {
        border: solid 1px gainsboro !important;
        outline: none !important;
        box-shadow: none !important;
    }

    .form-control,
    .form-search {
        background-color: rgb(245, 245, 245) !important;
    }

    .image-tryout {
        width: 100px;
        height: 80px;
        object-fit: cover;
    }

    .tryout-content .content.col-md-3 .image-tryout {
        width: 100%;
        height: 120px;
    }

    @media (min-width: 768px) {
        .image-tryout {
            width: 200px;
            height: 120px;
        }
    }

    .card {
        border: none;
    }


    /******  c746c227-00ed-4963-ad7d-86814ef264ca  *******/
</style>