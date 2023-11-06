import { Request, Response } from "express"
import User from "../../models/User"
import fs from 'fs';
import axios from 'axios';
import multer from "multer";
import { newUserValidate } from "../../validation/userValidate";
import { userInfo } from "os";

let code: string = "";
let verifyNumber: string = "";
// multer is used to upload files
const storage = multer.diskStorage({
    destination: function (req, file, cb) {
        cb(null, 'uploads/users/')
    },
    filename: function (req, file, cb) {
        const uniqueSuffix = Date.now() + '-' + Math.round(Math.random() * 1E9)
        cb(null, file.fieldname + '-' + uniqueSuffix + '.jpg')
    }
})
const upload = multer({ storage }).single('image');


export const reSandOtpNumber = async (req: Request, res: Response) => {
    const { resendNumber } = req.params;
    if (resendNumber === undefined) {
        return res.status(201).json({
            cond: 201,
            message: "number is required"
        })
    }
    const options = {
        method: 'GET',
        url: 'https://veriphone.p.rapidapi.com/verify',
        params: { phone: resendNumber },
        headers: {
            'X-RapidAPI-Key': '944de80e10mshada62bd966b1753p18e2b3jsnf31ded4dc82f',
            'X-RapidAPI-Host': 'veriphone.p.rapidapi.com'
        }
    };
    try {

        const { data } = await axios.get(options.url, { params: options.params, headers: options.headers });
        if (!data.phone_valid) {
            res.status(201).json({
                code: 201,
                message: "Number is not valid",
            })
        } else {
            var otp = (Math.floor(Math.random() * 10000) + 10000).toString().substring(1);
            code = otp;
            verifyNumber = resendNumber;
            res.status(200).json({
                code: 200,
                message: "OTP sent successfully",
                otp: otp
            })
        }
    }
    catch (err) {
        res.status(401).json({ err });
    }
}

export const sendOtpNumber = async (req: Request, res: Response) => {
    const { number } = req.body;
    if (number === undefined) {
        return res.status(422).json({
            cond: 422,
            status: false,
            message: "number is required"
        })
    }

    const isUserExist = await User.findOne({ number: number });
    var otp = (Math.floor(Math.random() * 10000) + 10000).toString().substring(1);
    code = otp;
    verifyNumber = number;
    if (isUserExist) {
        res.status(409).json({
            code: 409,
            status: true,
            message: "Number is already registered",
            otp: otp
        });
    } else {
        const user = new User({ number: number });
        try {
            await user.save();
            res.status(200).json({
                code: 200,
                message: "OTP sent successfully",
                otp: otp
            })
        }
        catch (err: any) {
            res.status(201).json({
                code: 201,
                message: err.message
            })
        }
    }
}

export const otpVerify = async (req: Request, res: Response) => {
    const { otp, number } = req.body;
    if (otp == code && number == verifyNumber) {
        const user = await User.find({ number: number })
        if (user.length > 0) {
            code = "";
            res.status(200).json({
                code: 200,
                message: "OTP verified successfully",
                data: user[0]
            })
        } else {
            code = "";
            res.status(200).json({
                code: 200,
                message: "OTP verified successfully",
            })
        }

    } else {
        res.status(409).json({
            code: 409,
            message: "OTP is not valid",
        })
    }
}



export const userSingUp = async (req: Request, res: Response) => {
    try {
        const a = [req.body.firstName, req.body.lastName,
        req.body.firstName + "-" + req.body.lastName, req.body.lastName + "-" + req.body.firstName]
        const rand = Math.round(Math.random() * 400)
        const rA = Math.floor(Math.random() * a.length);
        const userName = a[rA] + rand;
        let payload: any = {
            firstName: req.body.firstName,
            lastName: req.body.lastName,
            userName: userName,
            number: req.body.number,
            gender: req.body.gender,
            dob: req.body.dob
        }
        await newUserValidate.validateAsync(payload)
            .then(async (success: any) => {
                const isUserExist = await User.findOne({ number: req.body.number });
                if (isUserExist) {
                    await isUserExist.updateOne(payload);
                    res.status(200).json({
                        code: 200,
                        message: "User save information successfully",
                    })
                } else {
                    res.status(409).json({
                        code: 409,
                        message: "user not founded, please register your number",
                    })
                }
            }).catch((e: any) => {

                res.status(422).json({
                    code: 422,
                    message: e.message
                })
            })
    }
    catch (err: any) {
        res.status(409).json({
            code: 409,
            message: err.message
        })
    }
}


export const updateImage = async (req: Request, res: Response) => {
    upload(req, res, async (err) => {
        if (err) {
            return res.status(409).json({
                code: 409,
                message: err,
            });
        } else {
            try {
                const image = req.file?.filename;
                const isExistUser = await User.findOne({number:req.params.id});

                if (isExistUser) {
                    if (isExistUser.image!=='') {
                        
                        fs.unlinkSync("uploads" + isExistUser.image);
                    }
                    await isExistUser.updateOne({ image: "/users/" + image })
                    res.status(200).json({
                        code: 200,
                        message: "User image save successfully",
                    })
                } else {
                    if (req.file) {
                        fs.unlinkSync("uploads/users/" + image);
                    }
                    res.status(409).json({
                        code: 409,
                        message: "user not founded, please register your number",
                    })
                }

            } catch (error) {
                if (req.file) {
                    fs.unlinkSync("uploads/users/" + req.file?.filename);
                }
                return res.status(409).json({
                    code: 409,
                    message: error,
                });
            }
        }
    })
}


export const updateInterestTopics = async(req:Request,res: Response)=>{
    
    try{
        const isExistUser = await User.findOne({number:req.params.id})
        if(isExistUser){
            await isExistUser.updateOne({ interest: JSON.parse(req.body.interest)});
            res.status(200).json({
                code: 200,
                message: "User topic save successfully",
                data:isExistUser,
            })
        }else{
            res.status(409).json({
                code: 409,
                message: "user not founded, please register your number",
            })
        }
      
    }catch(error){
        return res.status(409).json({
            code: 409,
            message: error,
        });
    }

}


export const userLogin = async (req: Request, res: Response) => {
    const isUserExist = await User.findOne({ number: req.body.number });
    if (isUserExist) {
        res.status(200).json({
            code: 200,
            message: "User  login successfully",
            data: isUserExist
        })
    }
    else {
        res.status(400).json({
            code: 400,
            message: "number is correct",
        })
    }
}

export const getAllUsers = async (req: Request, res: Response) => {
    try {
        const allUsers = await User.find({});
        res.status(200).json({
            code: 200,
            message: "All users",
            data: allUsers
        })
    }
    catch (err) {
        res.status(500).json({
            code: 500,
            message: err
        })
    }

}

export const getUserById = async (req: Request, res: Response) => {

    try {
        const user = await User.findById(req.params.id);
        if (user) {
            res.status(200).json({
                code: 200,
                message: "User",
                data: user
            })
        } else {
            res.status(400).json({
                code: 400,
                message: "User not found",
            })
        }

    } catch (err) {
        res.status(500).json({
            code: 500,
            message: err
        })
    }

}


export const numberChecker = async (req: Request, res: Response) => {
    const { numbers } = req.body;
    let allNumbers:any = {};
    try {

        // for (let i = 0; i < numbers.length; i++) {
        for (let i = 0; i < 300; i++) {
            const allUsers:any = await User.find({ number: { $in: numbers[i]['contacts'].map((v:any,k:any)=> v) } }, { number: 1, _id: 1, image: 1});
            console.log("Scanning =>" + i +" NUmbers Length =>"+numbers.length + " ALL => " + allUsers);
            if (allUsers.length > 0) {
                const user={
                    id:allUsers[0]._id,
                    name:numbers[i]['name'],
                    number:allUsers[0].number,
                    image:allUsers[0].image,
                };
                allNumbers[user.number] =user;
            }
        }
        if (allNumbers) {
            res.status(200).json({
                code: 200,
                message: "All numbers",
                data: allNumbers
            })
        } else {
            res.status(409).json({
                code: 409,
                message: "No numbers found",
            })
        }

    } catch (e) {
        res.json({
            code: 409,
            message: e,
        });
    }
}
export const numberCheckerFLT = async (req: Request, res: Response) => {
    const { numbers } = req.body;
    let allNumbers:any = {};
    try {

        // for (let i = 0; i < numbers.length; i++) {
        for (let i = 0; i < 300; i++) {
            const allUsers:any = await User.find({ number: { $in: numbers[i]['contacts'].map((v:any,k:any)=> v.normalizedNumber) } }, { number: 1, _id: 1, image: 1});
            console.log("Scanning =>" + i +" NUmbers Length =>"+numbers.length + " ALL => " + allUsers);
            if (allUsers.length > 0) {
                const user={
                    id:allUsers[0]._id,
                    name:numbers[i]['name'],
                    number:allUsers[0].number,
                    image:allUsers[0].image,
                };
                allNumbers[user.number] =user;
            }
        }
        if (allNumbers) {
            res.status(200).json({
                code: 200,
                message: "All numbers",
                data: allNumbers
            })
        } else {
            res.status(409).json({
                code: 409,
                message: "No numbers found",
            })
        }

    } catch (e) {
        res.json({
            code: 409,
            message: e,
        });
    }
}