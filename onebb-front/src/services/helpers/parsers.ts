import moment from "moment";
import { useStore } from "vuex";
import { IUser } from '@/interfaces/obbApiInterface'
import { Ref } from "vue";
import { RouteLocationNormalizedLoaded, Router } from "vue-router";


export const parseUsername = (user: string|IUser, groupe?: string): string => {
  
  if (typeof user === 'object') {
    return user.user_group.html_code.replace("{{username}}", user.username);
  }
  
  if (groupe) { 
    return groupe.replace("{{username}}", user);
  }

  return '';
};

export const parseDate = (value: Date|string): string => {
  return moment(String(value)).format("YYYY-MM-DD");
};

export const parseAvatar = (img?: string): string => {
  const store = useStore();
  if(img) {
    return store.state.baseUrl + img;
  }

  return store.state.baseUrl + '/'  
}



export const currentPage = (route: RouteLocationNormalizedLoaded): number => {
  return route.params.page ? Number(route.params.page) : 1;
}

export const usePaginator = (ref: Ref, page: string, route: RouteLocationNormalizedLoaded, router: Router) => {

  const next = (name: string) => {
    const p =  route.params.page ?  Number(route.params.page) + 1 : 2;
    router.push({ name: name, params: { slug: route.params.slug, id: route.params.id, page: p} })
  }
  
  const prev = (name: string) => {
    const p =  route.params.page ?  Number(route.params.page) - 1 : 2;
    router.push({ name: name, params: { slug: route.params.slug, id: route.params.id, page: p} })
  }

  const paginator = (f?: string): boolean => {
    const limit = 20; // FIXME
    if (f === 'active') {
      if (currentPage(route) === 1) {
        return (ref.value?.length === limit);
      }
      return true;
    }
  
    if (f === 'isPrev') {
      return (currentPage(route) > 1);
    }
  
    if (f === 'isNext') {
      return (ref.value?.length === limit);
    }
  
    if (f === 'next') {
      next(page);
      return true;
    }
  
    if (f === 'prev') {
      prev(page);
      return true;
    }

    return false;
  }

  return paginator;
}