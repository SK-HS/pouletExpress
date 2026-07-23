@extends('layouts.master')
@section('content')


  <main class="main">

    <!-- Page Title -->
    <div class="page-title dark-background" data-aos="fade" style="background-image: url(assets/img/page-title-bg.jpg);">
      <div class="container position-relative">
        <h1>Details Produits</h1>
        <p></p>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="{{route('index')}}">Accueil</a></li>
            <li class="current"> Details Produits</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Service Details Section -->
    <section id="service-details" class="service-details section">

      <div class="container">

        <div class="row gy-4">
           {{-- <div class="col-lg-4 col-md-3" data-aos="fade-up" data-aos-delay="100">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Passer Votre commande<h5>
                  

              </div>
              </div>
              </div> --}}

          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            <div class="services-list">
                <h3>PRODUITS</h3>
              <a href="#" class="active">{{$produit->produit->nom}}</a>
              <a href="#" class="active">Type: {{$produit->produit->categorie->nom}}</a>
              <a href="#" class="active">Poids: {{$produit->produit->taille->taille}}</a>
              <a href="#" class="active">Quantité Disponible: {{$produit->quantite}}</a>
              <a href="#" class="active">Prix Unitaire: {{$produit->prix}} XOF</a>
            </div>
            <div class="services-list">
                <h3>FORNISSEUR</h3>
              <a href="#" >Nom: {{$produit->fournisseur->nom}}</a>
              <a href="#" >Type: {{$produit->fournisseur->type}}</a>
              <a href="#" >Téléphone: {{$produit->fournisseur->telephone}}</a>
              <a href="#">Quartier: {{$produit->fournisseur->quartier->nom_quartier}}</a>
              <a href="#">Adresse: {{$produit->fournisseur->adresse}}</a>
              <a class="btn btn-primary" style="color:white" href="{{route('Paiement-Commande', $produit->id)}}">Passer Votre Commande</a>
              
            </div>

            {{-- <h4>Enim qui eos rerum in delectus</h4>
            <p>Nam voluptatem quasi numquam quas fugiat ex temporibus quo est. Quia aut quam quod facere ut non occaecati ut aut. Nesciunt mollitia illum tempore corrupti sed eum reiciendis. Maxime modi rerum.</p> --}}
          </div>

          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            <img src="/storage/{{$produit->produit->image}}" alt="" class="img-fluid services-img">
            {{-- <h3>Temporibus et in vero dicta aut eius lidero plastis trand lined voluptas dolorem ut voluptas</h3> --}}
            <p>
             {{$produit->description}}
            </p>
            {{-- <ul>
              <li><i class="bi bi-check-circle"></i> <span>Aut eum totam accusantium voluptatem.</span></li>
              <li><i class="bi bi-check-circle"></i> <span>Assumenda et porro nisi nihil nesciunt voluptatibus.</span></li>
              <li><i class="bi bi-check-circle"></i> <span>Ullamco laboris nisi ut aliquip ex ea</span></li>
            </ul>
            <p>
              Est reprehenderit voluptatem necessitatibus asperiores neque sed ea illo. Deleniti quam sequi optio iste veniam repellat odit. Aut pariatur itaque nesciunt fuga.
            </p>
            <p>
              Sunt rem odit accusantium omnis perspiciatis officia. Laboriosam aut consequuntur recusandae mollitia doloremque est architecto cupiditate ullam. Quia est ut occaecati fuga. Distinctio ex repellendus eveniet velit sint quia sapiente cumque. Et ipsa perferendis ut nihil. Laboriosam vel voluptates tenetur nostrum. Eaque iusto cupiditate et totam et quia dolorum in. Sunt molestiae ipsum at consequatur vero. Architecto ut pariatur autem ad non cumque nesciunt qui maxime. Sunt eum quia impedit dolore alias explicabo ea.
            </p> --}}
            <div class="row">
              @if($produit->images)
              @foreach ($produit->images as $image)
                <div class="col-lg-4">
                  <a href="/storage/{{ $image }}" target="_blank">
                    <img src="/storage/{{ $image }}" alt="" class="img-fluid services-img">
                  </a>
                </div>
              @endforeach
              @endif
              
            </div>
          </div>
        

        </div>

      </div>

    </section><!-- /Service Details Section -->

  </main>

@endsection