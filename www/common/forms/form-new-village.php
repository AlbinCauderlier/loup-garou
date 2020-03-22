                        <form id="village-form" action="/controller/villages/new-village.php" method="post" class="mb-5 needs-validation" novalidate>
                            <h3>Le village</h3>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i data-feather="tag"></i>
                                    </div>
                                </div>
                                <input class="form-control" id="village-name" name="village-name" type="text" placeholder="Nom du village" required/>
                            </div>
                            <button class="btn btn-gradient btn-block p-2 my-3" type="submit">
                                <i data-feather="home" class="mr-2"></i> Créer le village
                            </button>
                        </form>
