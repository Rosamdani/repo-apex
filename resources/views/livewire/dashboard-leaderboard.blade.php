<div class="col-12">
    <div class="card h-100">
        <div class="card-body p-24">
            <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between mb-20">
                <h6 class="mb-2 fw-bold text-lg mb-0">Recent Bid</h6>
                <select class="form-select form-select-sm w-auto bg-base border text-secondary-light rounded-pill">
                    <option>All Items </option>
                    <option>New Item</option>
                    <option>Trending Item</option>
                    <option>Old Item</option>
                </select>
            </div>
            <div class="table-responsive scroll-sm">
                <div class="table-responsive scroll-sm">
                    <table class="table bordered-table sm-table mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Items </th>
                                <th scope="col">Price</th>
                                <th scope="col">Your Offer </th>
                                <th scope="col">Recent Offer</th>
                                <th scope="col">Time Left</th>
                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('assets/images/nft/nft-items-img1.png') }}" alt=""
                                            class="flex-shrink-0 me-12 w-40-px h-40-px rounded-circle me-12">
                                        <div class="flex-grow-1">
                                            <h6 class="text-md mb-0 fw-semibold">Spanky & Friends</h6>
                                            <span class="text-sm text-secondary-light fw-normal">Owned by
                                                ABC</span>
                                        </div>
                                    </div>
                                </td>
                                <td>1.44 ETH</td>
                                <td>3.053 ETH</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('assets/images/nft/nft-offer-img1.png') }}" alt=""
                                            class="flex-shrink-0 me-12 w-40-px h-40-px rounded-circle me-12">
                                        <div class="flex-grow-1">
                                            <h6 class="text-md mb-0 fw-semibold text-primary-light">1.44.00
                                                ETH</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>2h 5m 40s</td>
                                <td>
                                    <div class="d-inline-flex align-items-center gap-12">
                                        <button type="button" class="text-xl text-success-600"><i
                                                class="ri-edit-line"></i></button>
                                        <button type="button" class="text-xl text-danger-600 remove-btn"><i
                                                class="ri-delete-bin-6-line"></i></button>
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
