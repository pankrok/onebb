import type { IHydra, ISkinResponse } from '@/interfaces';
import useAxios from './useAxios'
import { instanceOf } from './helpers';

const boxes = { 
    Home: {
        top: null,
        bottom: null,
        left: null,
        right: null,   
    },
    Category: {
        top: null,
        bottom: null,
        left: null,
        right: null,   
    },
    Board: {
        top: null,
        bottom: null,
        left: null,
        right: null,   
    },
    Plot: {
        top: null,
        bottom: null,
        left: null,
        right: null,   
    },
    Page: {
        top: null,
        bottom: null,
        left: null,
        right: null,   
    },
    NewPlot: {
        top: null,
        bottom: null,
        left: null,
        right: null,   
    },
    SignUp: {
        top: null,
        bottom: null,
        left: null,
        right: null,   
    },
    Profile: {
        top: null,
        bottom: null,
        left: null,
        right: null,   
    },
    Userlist: {
        top: null,
        bottom: null,
        left: null,
        right: null,   
    },
    Validation: {
        top: null,
        bottom: null,
        left: null,
        right: null,   
    },
    UserConfig: {
        top: null,
        bottom: null,
        left: null,
        right: null,   
    },
    ResetPassword: {
        top: null,
        bottom: null,
        left: null,
        right: null,   
    },
    ResetPasswordValidation: {
        top: null,
        bottom: null,
        left: null,
        right: null,   
    },
    
    
  }

// @ts-ignore FIXME!!!
async function parseBoxes(boxlist) {
    return new Promise((done) => {
        // @ts-ignore FIXME!!!
        boxlist.forEach((val) => {
            if (val.page && val.position && val.id) {
                // @ts-ignore FIXME!!!
                if (boxes[val.page][val.position] === null) {
                    // @ts-ignore FIXME!!!
                    boxes[val.page][val.position] = {};
                }

                // @ts-ignore FIXME!!!
                boxes[val.page][val.position][val.id] = 
                { 
                    name: val.box.name, 
                    engine: val.box.engine,
                    html: val.box.html
                }
            }
        })
        done(true);
    })
    
}

export default async function useSkin() {
    const {axios} = useAxios();
    const {data} =  await axios.get<unknown>('skins?active=1');
    if (instanceOf<IHydra<ISkinResponse>>(data)) {
        // @ts-ignore FIXME!!!
        const handler = data['hydra:member'][0].skinBoxes
        await parseBoxes(handler)
        return boxes;
    }

    return null;
}